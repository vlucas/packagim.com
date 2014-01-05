<?php
use Entity\User;

// Database operations (should be CLI access only)
$app->path('task', function($request) use($app) {

    // Restrict access to CLI only or web with SUPER_KEY; or logged-in admin users
    if(
        (isset($app['user']) && $app['user']->isAdmin()) // User not admin
        || (!$app->request()->isCli() && $request->query('super_key') !== $request->env('SUPER_KEY')) // Invalid SUPER_KEY
    ) {
        return false;
    }

    $app->path('daemon', function($request) {
        // Remove time limit
        set_time_limit(0);

        // Show ALL errors
        error_reporting(-1);

        out('Starting Daemon...');

        // Run daemon
        while(true) {
            // Update users
            $this->run('GET', 'task/update_users_f1_info');

            // Wait a bit
            sleep(BULLET_ENV === 'development' ? 15 : 60);
        }
    });

    // Update Users FellowshipOne Information
    $this->path('update_users_f1_info', function($request) {
        $app = $this;
        $mapper =  $app['new_mapper'];

        out('Updating User FellowshipOne Info...');

        // Setup FellowshipOne
        $this['f1']->login2ndParty($this['f1']->settings->username, $this['f1']->settings->password);

        $users = $mapper->all('Entity\User')
            ->where(['date_refreshed <' => new \DateTime('-1 days')])
            ->orWhere(['date_refreshed' => null])
            ->limit(20);

        // Bail if no users
        if(count($users) === 0) {
            return;
        }

        out('>> Found ' . count($users) . ' users');

        // Fork process to work in parallel
        $results = Forker::map($users, function ($index, $user) use($app, $request) {
            // Have to establish new DB connection in forked process
            $mapper =  $app['new_mapper'];

            out('+ Attempting to update information for user ' . $user->name . '...');

            // Update user refreshed timestamp before updates in case of error (so it doesn't continually try to update user)
            $user->date_refreshed = new \DateTime();
            $mapper->update($user);

            // Update user info from FellowshipOne
            User::updateUserFromFellowshipOne($mapper, $user, $app['f1']);
            User::fetchHouseholdFromFellowshipOne($mapper, $user, $app['f1']);

            out('+ Updated information for user ' . $user->name);
            return true;
        });
        return;
    });
});

// Echo out messages about what's going on if in 'development' mode
function out($msg) {
    if(BULLET_ENV === 'development') {
        echo $msg . "\n";
    }
}

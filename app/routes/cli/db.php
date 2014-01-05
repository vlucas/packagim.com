<?php
// Database operations (should be CLI access only)
$app->path('db', function($request) use($app) {

    // Restrict access to CLI only or web with SUPER_KEY; or logged-in admin users
    if(
        (isset($app['user']) && $app['user']->isAdmin()) // User not admin
        || (!$app->request()->isCli() && $request->query('super_key') !== $request->env('SUPER_KEY')) // Invalid SUPER_KEY
    ) {
        return false;
    }

    $srcDir = BULLET_SRC_ROOT;

    $app->path('migrate', function($request) use($app, $srcDir) {
        // Perform db migration
        $entities = allEntitiesIn($srcDir);
        echo "--- Database Migration Started ---\n";
        foreach($entities as $entityClass) {
            echo "Migrating " . $entityClass . "...\n";
            $app['mapper']->migrate($entityClass);
        }
        echo "--- Database Migration Completed ---\n";

        return 200;
    });

    $app->path('reset', function($request) use($app, $srcDir) {
        // NEVER allow this action in production... (yikes!)
        if(BULLET_ENV === 'production') {
            return false;
        }

        // Perform db reset (drop all tables)
        $entities = allEntitiesIn($srcDir);
        echo "--- Database Reset Started ---\n";
        foreach($entities as $entityClass) {
        echo "Dropping " . $entityClass . "...\n";
        $app['mapper']->dropDatasource($entityClass);
        }
        echo "--- Database Reset Completed ---\n";

        return $app->run('cli', 'db/migrate');
    });

    $app->path('seeds', function($request) use($app, $srcDir) {
        require dirname(dirname(__DIR__)) . '/db/seeds.php';
        return 200;
    });
});

function allEntitiesIn($srcDir) {
    $ea = array();
    $finder = new Symfony\Component\Finder\Finder();
    $entities = $finder->files()->name('*.php')->in($srcDir . '/Entity');
    foreach($entities as $file) {
        // Derive class name by substracting path + ending '.php' from full file path
        $className = str_replace('/', '\\', str_replace(array($srcDir, '.php'), '', $file->getRealPath()));
        if(is_subclass_of($className, 'Spot\Entity', true)) {
            $ea[] = $className;
        }
    }
    sort($ea);
    return $ea;
}


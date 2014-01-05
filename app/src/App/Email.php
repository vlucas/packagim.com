<?php
namespace App;
use Spot;

class Email
{
    /**
     * Send an email based on given template and params
     */
    public function send($template, $params)
    {
        $app = app();
        if(!isset($app['emails'][$template])) {
            throw new \InvalidArgumentException("Template key '" . $template . "' not set in 'emails' config");
        }
        $email = $app['emails'][$template];
        // Config can set template and title, or they are auto-set based on the name
        $template = isset($email['template']) ? $email['template'] : $template;
        $title = isset($email['title']) ? $email['title'] : ucwords(str_replace('_', ' ', $template));

        // Build email template
        $messageHtml = $app->template($template, $params)
            ->path($app['template']['path'] . '/email')
            ->layout(false)
            ->content();

        // Build and send email
        $mail = Swift_Message::newInstance('[ChurchMint] ' . $email['title']);
        $mail->setTo($app['site']['email']['to'])
        ¦ ¦ ¦ ->setFrom($app['site']['email']['from'])
        ¦ ¦ ¦ ->setBody($messageHtml, 'text/html');
        // Send email
        return $app['mailer']->send($mail);
    }
}

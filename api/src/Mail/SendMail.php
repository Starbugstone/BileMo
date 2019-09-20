<?php
// /api/src/Mail/SendMail.php

namespace App\Mail;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class SendMail
 * @package App\Mail
 * needs composer require symfony/templating to be able to interpret the twig templating engine
 * also need to add in config/packages/framework
 * templating:
 *    engines:
 *       - twig
 * And need to add ADMIN_EMAIL="admin@localhost.dev" to the .env file
 */
class SendMail
{

    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Send a mail via the SwiftMailer component
     *
     * @param string $subject the subject of the mail
     * @param string $template the template to use for the mail. To be created in /App/Template, preferably in a specific email folder (needs to be passed)
     * @param object $mailData the data object to pass to the template
     * @param string $to the mail address to send the mail to
     * @param String|null $from who is the mail from
     * @return bool returns if the mail has been sent or not
     */
    public function send(string $subject, string $template, $mailData, string $to, String $from = null): bool
    {
        if ($from === null) {
            $from = 'admin@local.dev';
        }

        $message = (new Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to);
        $message
            ->setBody(
                $this->templating->render(
                    $template,
                    [
                        'mailData' => $mailData,
                        'subject' => $subject,
                    ]
                ),
                'text/html'
            );

        return $this->mailer->send($message) > 0;

    }

}
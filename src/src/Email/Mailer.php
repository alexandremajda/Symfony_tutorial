<?php

namespace App\Email;

use App\Entity\User;
use Twig\Environment;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(
        \Swift_Mailer $mailer,
        Environment $twig
    )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render(
            'email/confirmation.html.twig',
            [
                'user' => $user
            ]
        );

        $message = (new \Swift_Message("Hello from api platform"))
            ->setFrom('api-platform@api.com')
            ->setTo($user->getEmail())
            // ->setTo('alex.mjd123@gmail.com')
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}

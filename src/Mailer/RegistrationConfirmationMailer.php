<?php

namespace App\Mailer;

use App\Entity\User;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RegistrationConfirmationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private \Swift_Mailer $mailer;

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param User $user
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render('mailer/RegistrationConfirmation/index.html.twig', [
            'user' => $user
        ]);

        $message = (new Swift_Message('Hello from API PLATFORM'))
            ->setFrom('klisuric1995@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($body);

        $this->mailer->send($message);
    }
}
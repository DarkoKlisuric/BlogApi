<?php

namespace App\Controller;

use App\Service\UserConfirmationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MailerController
 * @package App\Controller
 * @Route("/mailer")
 */
class MailerController extends AppController
{
    /**
     * @param string $token
     * @param UserConfirmationService $confirmationService
     * @return RedirectResponse
     * @Route("/confirm-user/{token}", name="confirm-token")
     */
    public function confirmUser(string $token, UserConfirmationService $confirmationService)
    {
        $confirmationService->confirmUser($token);

        return $this->redirectToRoute('default');
    }
}
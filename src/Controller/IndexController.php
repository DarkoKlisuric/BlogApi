<?php

namespace App\Controller;

use App\Exception\InvalidConfirmationTokenException;
use App\Service\UserConfirmationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @Route("/")
 */
class IndexController extends AppController
{
    /**
     * @Route(name="default")
     */
    public function index()
    {
        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @param string $token
     * @param UserConfirmationService $confirmationService
     * @return RedirectResponse
     * @Route("/confirm-user/{token}", name="confirm-token")
     * @throws InvalidConfirmationTokenException
     */
    public function confirmUser(string $token, UserConfirmationService $confirmationService)
    {
        $confirmationService->confirmUser($token);

        return $this->redirectToRoute('default');
    }
}
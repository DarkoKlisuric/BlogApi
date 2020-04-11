<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConfirmationService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $manager)
    {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
    }

    public function confirmUser(string $confirmationToken)
    {
        $user = $this->userRepository->findOneBy(
            ['confiramtationToken' => $confirmationToken]);

        // User was NOT found by confiration token
        if (!$user) {
            throw new NotFoundHttpException();
        }

        $user->setEnabled(true);
        $user->setConfiramtationToken(null);

        $this->manager->flush();
    }
}
<?php

namespace App\Service;

use App\Exception\InvalidConfirmationTokenException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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
            throw new InvalidConfirmationTokenException();
        }

        $user->setEnabled(true);
        $user->setConfiramtationToken(null);

        $this->manager->flush();
    }
}
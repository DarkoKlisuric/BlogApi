<?php

namespace App\Handler;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface AuthoredEntityInterface
 * @package App\Handler
 */
interface AuthoredEntityInterface
{
    /**
     * @param UserInterface $user
     * @return AuthoredEntityInterface
     */
    public function setAuthor(UserInterface $user): AuthoredEntityInterface;
}
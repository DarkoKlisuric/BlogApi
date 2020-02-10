<?php

namespace App\Handler;

use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setAuthor(UserInterface $user): AuthoredEntityInterface;
}
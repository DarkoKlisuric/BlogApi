<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @var JWTTokenManagerInterface
     */
    private JWTTokenManagerInterface $tokenManager;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $manager
     * @param JWTTokenManagerInterface $tokenManager
     * @param RequestStack $request
     */
    public function __construct(ValidatorInterface $validator,
                                UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $manager,
                                JWTTokenManagerInterface $tokenManager,
                                RequestStack $request)
    {
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
        $this->tokenManager = $tokenManager;
        $this->request = $request;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @return UserPasswordEncoderInterface
     */
    public function getPasswordEncoder(): UserPasswordEncoderInterface
    {
        return $this->passwordEncoder;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    /**
     * @return JWTTokenManagerInterface
     */
    public function getTokenManager(): JWTTokenManagerInterface
    {
        return $this->tokenManager;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request->getCurrentRequest();
    }
}
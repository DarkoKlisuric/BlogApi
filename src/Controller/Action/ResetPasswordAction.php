<?php

namespace App\Controller\Action;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Controller\AppController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ResetPasswordAction
 * @package App\Controller\Action
 *
 * In API platform for custom operations,
 * we should create Action classes instead controller classes.
 */
class ResetPasswordAction extends AppController
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
     * ResetPasswordAction constructor.
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $manager
     * @param JWTTokenManagerInterface $tokenManager
     */
    public function __construct(ValidatorInterface $validator,
                                UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $manager,
                                JWTTokenManagerInterface $tokenManager)
    {
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param User $data
     *
     * Validator is only called after we return the data from this action!
     * Only hear is checks for user current password, but we've just modified it!
     * @return JsonResponse
     */
    public function __invoke(User $data)
    {
        // Entity is persisted automatically, only if validaton pass
        $this->validator->validate($data);

        $data->setPassword(
            $this->passwordEncoder->encodePassword(
                $data, $data->getNewPassword()
            )
        );

        $this->manager->flush();

        $token = $this->tokenManager->create($data);

        return new JsonResponse(['token' => $token]);
    }
}
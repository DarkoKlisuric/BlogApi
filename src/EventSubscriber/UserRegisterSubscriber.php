<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Security\TokenGenerator;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class PasswordHashSubscriber
 * @package App\EventSubscriber
 */
class UserRegisterSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @var TokenGenerator
     */
    private TokenGenerator $tokenGenerator;

    /**
     * @var \Swift_Mailer
     */
    private \Swift_Mailer $mailer;

    /**
     * PasswordHashSubscriber constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                TokenGenerator $tokenGenerator,
                                \Swift_Mailer $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['userRegistered', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function userRegistered(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $methodChecker = static function () use ($method) {
            return in_array($method, [Request::METHOD_POST], true);
        };

        if (!$user instanceof User || !$methodChecker) {
            return;
        }
        // Hashing password
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );

        // Create confirmation token
        $user->setConfiramtationToken(
            $this->tokenGenerator->getRandomSecureToken()
        );

        // Send e-mail
        $this->sendMail();
    }
}
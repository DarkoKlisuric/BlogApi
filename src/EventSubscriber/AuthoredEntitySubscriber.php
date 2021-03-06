<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Handler\AuthoredEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthoredEntitySubscriber
 * @package App\EventSubscriber
 */
class AuthoredEntitySubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $storage;

    /**
     * AuthoredEntitySubscriber constructor.
     * @param TokenStorageInterface $storage
     */
    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function getAuthenticatedUser(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();

        $method = $event->getRequest()->getMethod();

        $token = $this->storage->getToken();

        if (null === $token) {
            return;
        }

        /** @var UserInterface $author */
        $author = $token->getUser();

        if ((!$entity instanceof AuthoredEntityInterface) || $method !==  Request::METHOD_POST) {
            return;
        }

        $entity->setAuthor($author);
    }
}
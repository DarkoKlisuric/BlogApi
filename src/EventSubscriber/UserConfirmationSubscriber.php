<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Security\UserConfirmation;
use App\Service\UserConfirmationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriber implements EventSubscriberInterface
{
    private const ROUTE = 'api_user_confirmations_post_collection';

    /**
     * @var UserConfirmationService
     */
    private UserConfirmationService $confirmationService;

    public function __construct(UserConfirmationService $confirmationService)
    {

        $this->confirmationService = $confirmationService;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE]
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function confirmUser(ViewEvent $event)
    {
        $request = $event->getRequest();

        if (self::ROUTE !== $request->get('_route')) {
            return;
        }

        /** @var UserConfirmation $confirmationToken */
        $confirmationToken = $event->getControllerResult();

        $this->confirmationService->confirmUser($confirmationToken->confirmationToken);

        $event->setResponse(new JsonResponse(null,Response::HTTP_OK));
    }
}
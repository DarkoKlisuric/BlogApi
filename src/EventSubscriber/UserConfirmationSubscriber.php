<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\UserConfirmation;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriber implements EventSubscriberInterface
{
    private const ROUTE = 'api_user_confirmations_post_collection';

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
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

        $user = $this->userRepository->findOneBy(
            ['confiramtationToken' => $confirmationToken->confirmationToken]);

        // User was NOT found by confiration token
        if (!$user) {
           throw new NotFoundHttpException();
        }

        $user->setEnabled(true);
        $user->setConfiramtationToken(null);

        $this->entityManager->flush();

        $event->setResponse(new JsonResponse(null,Response::HTTP_OK));
    }
}
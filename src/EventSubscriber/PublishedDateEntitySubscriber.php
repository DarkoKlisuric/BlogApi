<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Handler\PublishedDateEntityInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class PublishedDateEntitySubscriber
 * @package App\EventSubscriber
 */
class PublishedDateEntitySubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDatePublished', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @throws Exception
     */
    public function setDatePublished(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$entity instanceof PublishedDateEntityInterface || $method !==  Request::METHOD_POST) {
            return;
        }

        $entity->setPublished(new \DateTime());
    }
}
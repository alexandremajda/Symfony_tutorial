<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\PublishedDateEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Allows you to set the current datetime
 */
class PublishedDateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDatePublished', EventPriorities::PRE_WRITE]
        ];
    }
 
    /**
     * If the event launcher entity implements the PublishedDateEntityInterface and with post method
     * Allows you to set the publishedDate with the current dateTime
     *
     * @param ViewEvent $event
     * @return void
     */
    public function setDatePublished(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$entity instanceof PublishedDateEntityInterface
            || Request::METHOD_POST !== $method)
            return;

        $entity->setPublished(new \DateTime());
    }
}

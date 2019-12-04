<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\AuthorEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * This class allows you to set the current user as author automatically
 */
class AuthorEntitySubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage; 
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * Set the current user as author if the request send the post's method
     * By the way, the entity needs to implement the AuthorEntityInterface
     *
     * @param ViewEvent $event
     * @return void
     */
    public function getAuthenticatedUser(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $author = $this->tokenStorage->getToken()->getUser();

        if (!$entity instanceof AuthorEntityInterface
            || Request::METHOD_POST !== $method)
            return;

        $entity->setAuthor($author);
    }
}

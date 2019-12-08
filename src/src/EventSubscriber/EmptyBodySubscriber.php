<?php

namespace App\EventSubscriber;

use App\Exception\EmptyBodyException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class EmptyBodySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            /**
             * The error occurs before the deserialization, because in the case of tutorial, we send an empty Json
             * So that trigger the case where fields shouldn't be blank
             */ 
            // KernelEvents::REQUEST => ['handleEmptyBody', EventPriorities::PRE_DESERIALIZE]
            
            KernelEvents::REQUEST => ['handleEmptyBody', EventPriorities::POST_DESERIALIZE]
        ];
    }

    public function handleEmptyBody(RequestEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();

        // If the request does not contain any brackets, it's considered as a GET request
        if (!in_array($method, [Request::METHOD_POST, Request::METHOD_PUT]))
            return;

        $data = $request->get('data');
        // var_dump($data->getId());

        // The api provides an empty object, so never go in 
        // if (null === $data->getId())
        if (null === $data)
            throw new EmptyBodyException();
    }
}

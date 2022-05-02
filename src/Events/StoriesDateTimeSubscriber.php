<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Entity\Stories;

class StoriesDateTimeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::VIEW => ['setCreatedAtDateTime', EventPriorities::PRE_WRITE]
        ];
    }

    public function setCreatedAtDateTime(ViewEvent $event)
    {
        $stories = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if($stories instanceof Stories)
        {
            /**
             * On Stories Creation it will create an CreateAt and UpdateAt
             */
            if($method =="POST")
            {
                $isCreated = $stories->getCreatedAt();
                if(!$isCreated) {
                    $stories->setCreatedAt(new \DateTime());
                }
                $stories->setUpdatedAt(new \DateTime());
            }
            /**
             * On Stories modification update the updatedAt
             */
            if($method =="PUT" || $method == "PATCH")
            {
                $stories->setUpdatedAt(new \DateTime());
            }
        }
    }
}
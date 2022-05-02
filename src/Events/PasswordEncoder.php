<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoder implements EventSubscriberInterface
{

    public function __construct(){

    }
    public static function getSubscribedEvents()
    {
        return[
          KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function encodePassword(ViewEvent $event, UserPasswordHasherInterface $passwordHasher){
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $plainPassword =  $user->getPassword();
        if ($user instanceof App\Entity\User && $method=="POST"){
            $hashed = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashed);
        }
    }
}
<?php
// src/EventListener/JWTCreatedListener.php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event) : void
    {
        $user = $event->getUser();
        $payload = $event->getData();
        // Ajoutez les rôles de l'utilisateur au payload
        $payload['roles'] = $user->getRoles();

        $event->setData($payload);
    }
}
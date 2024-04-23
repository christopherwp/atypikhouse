<?php

// src/EventSubscriber/JWTCreatedSubscriber.php

namespace App\EventSubscriber;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTCreatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onJWTCreated',
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
    // Vérifiez que l'utilisateur est de la classe attendue
    if (!$user instanceof User) {
        throw new \LogicException('User object is not an instance of expected class.');
    }
        $payload = $event->getData();
        
        // Ajouter l'ID utilisateur au payload du token
        $payload['id'] = $user->getId();
        // $payload['adresse'] =$user->getAdresse();

        $event->setData($payload);
    }

}
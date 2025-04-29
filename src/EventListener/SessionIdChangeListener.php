<?php
// src/EventListener/SessionIdChangeListener.php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Listener pour gérer les changements d'ID de session
 */
class SessionIdChangeListener implements EventSubscriberInterface
{
    /**
     * Définit les événements auxquels ce listener s'abonne
     * 
     * @return array Liste des événements et des méthodes associées
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', 10],
        ];
    }

    /**
     * Méthode exécutée lors de la génération de la réponse
     * 
     * @param ResponseEvent $event L'événement de réponse
     * @return void
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
        
        // Garantir que les en-têtes de session sont correctement traités
        $response = $event->getResponse();
        if ($response->headers->has('Set-Cookie')) {
            $response->headers->set('Cache-Control', 'no-cache, private');
        }
    }
}

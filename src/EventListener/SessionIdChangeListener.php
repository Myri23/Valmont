<?php
// src/EventListener/SessionIdChangeListener.php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SessionIdChangeListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', 10],
        ];
    }

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
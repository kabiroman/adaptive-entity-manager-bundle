<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\EventSubscriber;

use Kabiroman\AdaptiveEntityManagerBundle\Event\ManagerRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ManagerRegistrySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ManagerRegisteredEvent::class => 'onManagerRegistered',
        ];
    }

    public function onManagerRegistered(ManagerRegisteredEvent $event): void
    {
        $this->logger->info(sprintf(
            '[ManagerRegistrySubscriber] Manager "%s" of type "%s" has been registered.',
            $event->getManagerName(),
            get_class($event->getManager())
        ));
    }
}

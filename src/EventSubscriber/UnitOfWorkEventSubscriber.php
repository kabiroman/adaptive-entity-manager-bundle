<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\EventSubscriber;

use Kabiroman\AEM\Event\PostPersistEvent;
use Kabiroman\AEM\Event\PostRemoveEvent;
use Kabiroman\AEM\Event\PostUpdateEvent;
use Kabiroman\AEM\Event\PrePersistEvent;
use Kabiroman\AEM\Event\PreRemoveEvent;
use Kabiroman\AEM\Event\PreUpdateEvent;
use Kabiroman\AdaptiveEntityManagerBundle\Event\PostPersistEntityEvent;
use Kabiroman\AdaptiveEntityManagerBundle\Event\PostRemoveEntityEvent;
use Kabiroman\AdaptiveEntityManagerBundle\Event\PostUpdateEntityEvent;
use Kabiroman\AdaptiveEntityManagerBundle\Event\PrePersistEntityEvent;
use Kabiroman\AdaptiveEntityManagerBundle\Event\PreRemoveEntityEvent;
use Kabiroman\AdaptiveEntityManagerBundle\Event\PreUpdateEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

class UnitOfWorkEventSubscriber implements EventSubscriberInterface
{
    private SymfonyEventDispatcherInterface $eventDispatcher;

    public function __construct(SymfonyEventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PrePersistEvent::class => 'onPrePersist',
            PostPersistEvent::class => 'onPostPersist',
            PreUpdateEvent::class => 'onPreUpdate',
            PostUpdateEvent::class => 'onPostUpdate',
            PreRemoveEvent::class => 'onPreRemove',
            PostRemoveEvent::class => 'onPostRemove',
        ];
    }

    public function onPrePersist(PrePersistEvent $event): void
    {
        $symfonyEvent = new PrePersistEntityEvent($event->getEntity(), $event);
        $this->eventDispatcher->dispatch($symfonyEvent, PrePersistEntityEvent::NAME);
    }

    public function onPostPersist(PostPersistEvent $event): void
    {
        $symfonyEvent = new PostPersistEntityEvent($event->getEntity(), $event);
        $this->eventDispatcher->dispatch($symfonyEvent, PostPersistEntityEvent::NAME);
    }

    public function onPreUpdate(PreUpdateEvent $event): void
    {
        $symfonyEvent = new PreUpdateEntityEvent($event->getEntity(), $event);
        $this->eventDispatcher->dispatch($symfonyEvent, PreUpdateEntityEvent::NAME);
    }

    public function onPostUpdate(PostUpdateEvent $event): void
    {
        $symfonyEvent = new PostUpdateEntityEvent($event->getEntity(), $event);
        $this->eventDispatcher->dispatch($symfonyEvent, PostUpdateEntityEvent::NAME);
    }

    public function onPreRemove(PreRemoveEvent $event): void
    {
        $symfonyEvent = new PreRemoveEntityEvent($event->getEntity(), $event);
        $this->eventDispatcher->dispatch($symfonyEvent, PreRemoveEntityEvent::NAME);
    }

    public function onPostRemove(PostRemoveEvent $event): void
    {
        $symfonyEvent = new PostRemoveEntityEvent($event->getEntity(), $event);
        $this->eventDispatcher->dispatch($symfonyEvent, PostRemoveEntityEvent::NAME);
    }
}

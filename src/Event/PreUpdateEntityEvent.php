<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Kabiroman\AEM\Event\PreUpdateEvent as AemPreUpdateEvent;
use Symfony\Contracts\EventDispatcher\Event;
use Psr\EventDispatcher\StoppableEventInterface;

class PreUpdateEntityEvent extends Event implements StoppableEventInterface
{
    public const NAME = 'aem.pre_update_entity';

    private object $entity;
    private AemPreUpdateEvent $originalEvent;

    public function __construct(object $entity, AemPreUpdateEvent $originalEvent)
    {
        $this->entity = $entity;
        $this->originalEvent = $originalEvent;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getOriginalEvent(): AemPreUpdateEvent
    {
        return $this->originalEvent;
    }

    public function isPropagationStopped(): bool
    {
        return $this->originalEvent->isPropagationStopped();
    }

    public function stopPropagation(): void
    {
        $this->originalEvent->stopPropagation();
    }
}

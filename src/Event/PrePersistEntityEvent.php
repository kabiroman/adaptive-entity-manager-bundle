<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Kabiroman\AEM\Event\PrePersistEvent as AemPrePersistEvent;
use Symfony\Contracts\EventDispatcher\Event;
use Psr\EventDispatcher\StoppableEventInterface;

class PrePersistEntityEvent extends Event implements StoppableEventInterface
{
    public const NAME = 'aem.pre_persist_entity';

    private object $entity;
    private AemPrePersistEvent $originalEvent;

    public function __construct(object $entity, AemPrePersistEvent $originalEvent)
    {
        $this->entity = $entity;
        $this->originalEvent = $originalEvent;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getOriginalEvent(): AemPrePersistEvent
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

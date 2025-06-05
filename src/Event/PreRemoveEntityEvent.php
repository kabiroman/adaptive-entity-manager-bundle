<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Kabiroman\AEM\Event\PreRemoveEvent as AemPreRemoveEvent;
use Symfony\Contracts\EventDispatcher\Event;
use Psr\EventDispatcher\StoppableEventInterface;

class PreRemoveEntityEvent extends Event implements StoppableEventInterface
{
    public const NAME = 'aem.pre_remove_entity';

    private object $entity;
    private AemPreRemoveEvent $originalEvent;

    public function __construct(object $entity, AemPreRemoveEvent $originalEvent)
    {
        $this->entity = $entity;
        $this->originalEvent = $originalEvent;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getOriginalEvent(): AemPreRemoveEvent
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

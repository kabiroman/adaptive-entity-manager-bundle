<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Kabiroman\AEM\Event\PostRemoveEvent as AemPostRemoveEvent;
use Symfony\Contracts\EventDispatcher\Event;
use Psr\EventDispatcher\StoppableEventInterface;

class PostRemoveEntityEvent extends Event implements StoppableEventInterface
{
    public const NAME = 'aem.post_remove_entity';

    private object $entity;
    private AemPostRemoveEvent $originalEvent;

    public function __construct(object $entity, AemPostRemoveEvent $originalEvent)
    {
        $this->entity = $entity;
        $this->originalEvent = $originalEvent;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getOriginalEvent(): AemPostRemoveEvent
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

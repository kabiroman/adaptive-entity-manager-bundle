<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Kabiroman\AEM\Event\PostUpdateEvent as AemPostUpdateEvent;
use Symfony\Contracts\EventDispatcher\Event;
use Psr\EventDispatcher\StoppableEventInterface;

class PostUpdateEntityEvent extends Event implements StoppableEventInterface
{
    public const NAME = 'aem.post_update_entity';

    private object $entity;
    private AemPostUpdateEvent $originalEvent;

    public function __construct(object $entity, AemPostUpdateEvent $originalEvent)
    {
        $this->entity = $entity;
        $this->originalEvent = $originalEvent;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getOriginalEvent(): AemPostUpdateEvent
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

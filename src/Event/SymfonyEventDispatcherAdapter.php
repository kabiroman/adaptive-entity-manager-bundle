<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

class SymfonyEventDispatcherAdapter implements PsrEventDispatcherInterface
{
    public function __construct(
        private readonly SymfonyEventDispatcherInterface $symfonyEventDispatcher
    ) {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event): object
    {
        $this->symfonyEventDispatcher->dispatch($event);

        return $event;
    }
}

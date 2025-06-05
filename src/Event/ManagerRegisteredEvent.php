<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Kabiroman\AEM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ManagerRegisteredEvent extends Event
{
    public const NAME = 'aem.manager_registered';

    public function __construct(
        private readonly string $managerName,
        private readonly EntityManagerInterface $manager
    ) {
    }

    public function getManagerName(): string
    {
        return $this->managerName;
    }

    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }
}

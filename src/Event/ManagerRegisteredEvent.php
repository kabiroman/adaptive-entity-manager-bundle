<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Event;

use Kabiroman\AEM\EntityManagerInterface;

class ManagerRegisteredEvent
{
    public const NAME = 'adaptive_entity_manager.manager_registered';

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

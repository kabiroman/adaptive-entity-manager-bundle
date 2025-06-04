<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Service;

use InvalidArgumentException;
use Kabiroman\AEM\EntityManagerInterface;

class ManagerRegistry implements ManagerRegistryInterface
{
    /**
     * @var array<string, EntityManagerInterface>
     */
    private array $managers = [];

    public function addManager(string $name, EntityManagerInterface $manager): void
    {
        $this->managers[$name] = $manager;
    }

    public function getManager(string $name): ?EntityManagerInterface
    {
        return $this->managers[$name] ?? null;
    }

    public function getManagers(): array
    {
        return $this->managers;
    }

    public function getManagerByEntityClass(string $entityClass): ?EntityManagerInterface
    {
        foreach ($this->managers as $manager) {
            try {
                $manager->getClassMetadata($entityClass);
                return $manager;
            } catch (InvalidArgumentException) {
                continue;
            }
        }
        return null;
    }
}

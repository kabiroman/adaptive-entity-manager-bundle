<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Service;

use Kabiroman\AEM\EntityManagerInterface;

interface ManagerRegistryInterface
{
    public function getManager(string $name): ?EntityManagerInterface;

    /**
     * @return array<string, EntityManagerInterface>
     */
    public function getManagers(): array;

    public function getManagerByEntityClass(string $entityClass): ?EntityManagerInterface;
}

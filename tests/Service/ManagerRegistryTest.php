<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Tests\Service;

use Kabiroman\AdaptiveEntityManagerBundle\Service\ManagerRegistry;
use Kabiroman\AEM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class ManagerRegistryTest extends TestCase
{
    private ManagerRegistry $managerRegistry;

    protected function setUp(): void
    {
        $this->managerRegistry = new ManagerRegistry();
    }

    public function testAddAndGetManager(): void
    {
        $managerName = 'default';
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);

        $this->managerRegistry->addManager($managerName, $mockEntityManager);

        $this->assertSame($mockEntityManager, $this->managerRegistry->getManager($managerName));
    }

    public function testGetNonExistentManager(): void
    {
        $this->assertNull($this->managerRegistry->getManager('non_existent'));
    }

    public function testGetManagers(): void
    {
        $manager1Name = 'manager1';
        $manager2Name = 'manager2';
        $mockEntityManager1 = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager2 = $this->createMock(EntityManagerInterface::class);

        $this->managerRegistry->addManager($manager1Name, $mockEntityManager1);
        $this->managerRegistry->addManager($manager2Name, $mockEntityManager2);

        $managers = $this->managerRegistry->getManagers();

        $this->assertCount(2, $managers);
        $this->assertArrayHasKey($manager1Name, $managers);
        $this->assertArrayHasKey($manager2Name, $managers);
        $this->assertSame($mockEntityManager1, $managers[$manager1Name]);
        $this->assertSame($mockEntityManager2, $managers[$manager2Name]);
    }

    public function testGetManagerByEntityClassFound(): void
    {
        $entityClass = 'App\\Entity\\User';
        $manager1Name = 'manager1';
        $manager2Name = 'manager2';

        $mockEntityManager1 = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager1->method('getClassMetadata')
            ->willThrowException(new InvalidArgumentException('Entity not found in manager1'));

        $mockEntityManager2 = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager2->method('getClassMetadata')
            ->with($entityClass)
            ->willReturn([]); // Return something not null to indicate found

        $this->managerRegistry->addManager($manager1Name, $mockEntityManager1);
        $this->managerRegistry->addManager($manager2Name, $mockEntityManager2);

        $foundManager = $this->managerRegistry->getManagerByEntityClass($entityClass);

        $this->assertSame($mockEntityManager2, $foundManager);
    }

    public function testGetManagerByEntityClassNotFound(): void
    {
        $entityClass = 'App\\Entity\\NonExistent';

        $mockEntityManager1 = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager1->method('getClassMetadata')
            ->willThrowException(new InvalidArgumentException('Entity not found in manager1'));

        $mockEntityManager2 = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager2->method('getClassMetadata')
            ->willThrowException(new InvalidArgumentException('Entity not found in manager2'));

        $this->managerRegistry->addManager('manager1', $mockEntityManager1);
        $this->managerRegistry->addManager('manager2', $mockEntityManager2);

        $foundManager = $this->managerRegistry->getManagerByEntityClass($entityClass);

        $this->assertNull($foundManager);
    }
}

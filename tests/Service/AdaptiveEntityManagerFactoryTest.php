<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Tests\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AdaptiveEntityManagerBundle\Service\AdaptiveEntityManagerFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class AdaptiveEntityManagerFactoryTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManager;
    private Connection|MockObject $connection;
    private array $config;
    private AdaptiveEntityManagerFactory $factory;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getConnection')->willReturn($this->connection);

        $this->config = [
            'config' => [
                'entity_folder' => '/path/to/entities',
                'entity_namespace' => 'App\\Entity',
                'cache_folder' => '/path/to/cache'
            ]
        ];

        $this->factory = new AdaptiveEntityManagerFactory(
            $this->entityManager,
            $this->config
        );
    }

    public function testCreateReturnsAdaptiveEntityManager(): void
    {
        $manager = $this->factory->create();

        $this->assertInstanceOf(AdaptiveEntityManager::class, $manager);
    }

    public function testCreateReturnsSameInstance(): void
    {
        $firstManager = $this->factory->create();
        $secondManager = $this->factory->create();

        $this->assertSame($firstManager, $secondManager, 'Factory should return the same instance on subsequent calls');
    }

    public function testCreateWithValidConfig(): void
    {
        $manager = $this->factory->create();

        $this->assertInstanceOf(AdaptiveEntityManager::class, $manager);
        
        // Проверяем, что конфигурация правильно применена
        $reflection = new \ReflectionClass($manager);
        $configProperty = $reflection->getProperty('config');
        $configProperty->setAccessible(true);
        $actualConfig = $configProperty->getValue($manager);

        $this->assertEquals($this->config['config']['entity_folder'], $actualConfig->getEntityFolder());
        $this->assertEquals($this->config['config']['entity_namespace'], $actualConfig->getEntityNamespace());
        $this->assertEquals($this->config['config']['cache_folder'], $actualConfig->getCacheFolder());
    }
} 
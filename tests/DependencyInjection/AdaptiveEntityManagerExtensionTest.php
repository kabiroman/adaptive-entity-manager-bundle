<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Tests\DependencyInjection;

use Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection\AdaptiveEntityManagerExtension;
use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AEM\ValueObject\Converter\ValueObjectConverterRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdaptiveEntityManagerExtensionTest extends TestCase
{
    private AdaptiveEntityManagerExtension $extension;
    private ContainerBuilder $container;

    private string $tempEntitiesDir;

    protected function setUp(): void
    {
        $this->extension = new AdaptiveEntityManagerExtension();
        $this->container = new ContainerBuilder();

        // Create temporary entities directory for tests
        $this->tempEntitiesDir = sys_get_temp_dir() . '/test_entities_' . uniqid();
        mkdir($this->tempEntitiesDir, 0777, true);

        // Mock required services and parameters
        $this->container->register('Psr\\EventDispatcher\\EventDispatcherInterface');
        $this->container->setParameter('kernel.project_dir', __DIR__ . '/../..');
        $this->container->setParameter('kernel.cache_dir', __DIR__ . '/../../var/cache');
    }

    protected function tearDown(): void
    {
        // Clean up temporary directory
        if (is_dir($this->tempEntitiesDir)) {
            rmdir($this->tempEntitiesDir);
        }
    }

    public function testValueObjectRegistryIsRegistered(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'default' => [
                        'entities_dir' => $this->tempEntitiesDir,
                        'entities_namespace' => 'App\\Entity\\',
                        'enable_value_objects' => true,
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);

        $this->assertTrue($this->container->hasDefinition('adaptive_entity_manager.value_object_registry'));

        $registryDefinition = $this->container->getDefinition('adaptive_entity_manager.value_object_registry');
        $this->assertEquals(ValueObjectConverterRegistry::class, $registryDefinition->getClass());
    }

    public function testEntityManagerWithValueObjectSupport(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'default' => [
                        'entities_dir' => $this->tempEntitiesDir,
                        'entities_namespace' => 'App\\Entity\\',
                        'enable_value_objects' => true,
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);

        $this->assertTrue($this->container->hasDefinition('adaptive_entity_manager.default_entity_manager'));

        $managerDefinition = $this->container->getDefinition('adaptive_entity_manager.default_entity_manager');
        $this->assertEquals(AdaptiveEntityManager::class, $managerDefinition->getClass());

        $arguments = $managerDefinition->getArguments();

        // ValueObject registry should be the last argument
        $lastArgument = end($arguments);
        $this->assertInstanceOf(Reference::class, $lastArgument);
        $this->assertEquals('adaptive_entity_manager.value_object_registry', (string)$lastArgument);
    }

    public function testEntityManagerWithoutValueObjectSupport(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'default' => [
                        'entities_dir' => $this->tempEntitiesDir,
                        'entities_namespace' => 'App\\Entity\\',
                        'enable_value_objects' => false,
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);

        $this->assertTrue($this->container->hasDefinition('adaptive_entity_manager.default_entity_manager'));

        $managerDefinition = $this->container->getDefinition('adaptive_entity_manager.default_entity_manager');
        $arguments = $managerDefinition->getArguments();

        // ValueObject registry should be null (last argument)
        $lastArgument = end($arguments);
        $this->assertNull($lastArgument);
    }

    public function testValueObjectSupportEnabledByDefault(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'default' => [
                        'entities_dir' => $this->tempEntitiesDir,
                        'entities_namespace' => 'App\\Entity\\',
                        // enable_value_objects not specified - should default to true
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);

        $managerDefinition = $this->container->getDefinition('adaptive_entity_manager.default_entity_manager');
        $arguments = $managerDefinition->getArguments();

        // ValueObject registry should be present (last argument)
        $lastArgument = end($arguments);
        $this->assertInstanceOf(Reference::class, $lastArgument);
        $this->assertEquals('adaptive_entity_manager.value_object_registry', (string)$lastArgument);
    }
}

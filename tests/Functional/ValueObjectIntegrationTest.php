<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Tests\Functional;

use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AEM\ValueObject\Common\Email;
use Kabiroman\AEM\ValueObject\Converter\ValueObjectConverterRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection\AdaptiveEntityManagerExtension;

class ValueObjectIntegrationTest extends TestCase
{
    private ContainerBuilder $container;
    private AdaptiveEntityManagerExtension $extension;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->extension = new AdaptiveEntityManagerExtension();

        // Register required services and parameters
        $this->container->register('Psr\\EventDispatcher\\EventDispatcherInterface');
        $this->container->register('event_dispatcher', 'Symfony\\Component\\EventDispatcher\\EventDispatcher');
        $this->container->setParameter('kernel.project_dir', __DIR__ . '/../..');
        $this->container->setParameter('kernel.cache_dir', __DIR__ . '/../../var/cache');
    }

    public function testValueObjectRegistryIsAvailable(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'test' => [
                        'entities_dir' => __DIR__ . '/fixtures/entities',
                        'entities_namespace' => 'App\\Entity\\Test\\',
                        'enable_value_objects' => true,
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);
        $this->container->compile();

        // Check ValueObject registry service
        $this->assertTrue($this->container->has('adaptive_entity_manager.value_object_registry'));

        $registry = $this->container->get('adaptive_entity_manager.value_object_registry');
        $this->assertInstanceOf(ValueObjectConverterRegistry::class, $registry);
    }

    public function testEntityManagerHasValueObjectSupport(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'test' => [
                        'entities_dir' => __DIR__ . '/fixtures/entities',
                        'entities_namespace' => 'App\\Entity\\Test\\',
                        'enable_value_objects' => true,
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);
        $this->container->compile();

        // Check entity manager service
        $this->assertTrue($this->container->has('adaptive_entity_manager.test_entity_manager'));

        $entityManager = $this->container->get('adaptive_entity_manager.test_entity_manager');
        $this->assertInstanceOf(AdaptiveEntityManager::class, $entityManager);

        // Check ValueObject support
        $this->assertTrue($entityManager->hasValueObjectSupport());

        $registry = $entityManager->getValueObjectRegistry();
        $this->assertInstanceOf(ValueObjectConverterRegistry::class, $registry);

        // Check built-in Email ValueObject support
        $this->assertTrue($registry->supports(Email::class));
    }

    public function testEntityManagerWithoutValueObjectSupport(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'test' => [
                        'entities_dir' => __DIR__ . '/fixtures/entities',
                        'entities_namespace' => 'App\\Entity\\Test\\',
                        'enable_value_objects' => false,
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);
        $this->container->compile();

        $entityManager = $this->container->get('adaptive_entity_manager.test_entity_manager');
        $this->assertInstanceOf(AdaptiveEntityManager::class, $entityManager);

        // Check ValueObject support is disabled
        $this->assertFalse($entityManager->hasValueObjectSupport());
        $this->assertNull($entityManager->getValueObjectRegistry());
    }

    public function testValueObjectRegistryWithBuiltInConverters(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'test' => [
                        'entities_dir' => __DIR__ . '/fixtures/entities',
                        'entities_namespace' => 'App\\Entity\\Test\\',
                        'enable_value_objects' => true,
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);
        $this->container->compile();

        $registry = $this->container->get('adaptive_entity_manager.value_object_registry');
        $this->assertInstanceOf(ValueObjectConverterRegistry::class, $registry);

        // Test built-in ValueObject support
        $supportedClasses = [
            Email::class,
            \Kabiroman\AEM\ValueObject\Common\Money::class,
            \Kabiroman\AEM\ValueObject\Common\UserId::class,
        ];

        foreach ($supportedClasses as $class) {
            $this->assertTrue(
                $registry->supports($class),
                "Registry should support {$class}"
            );
        }
    }

    public function testConfigurationDefaults(): void
    {
        $config = [
            'adaptive_entity_manager' => [
                'entity_managers' => [
                    'test' => [
                        'entities_dir' => __DIR__ . '/fixtures/entities',
                        'entities_namespace' => 'App\\Entity\\Test\\',
                        // enable_value_objects not specified - should default to true
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);
        $this->container->compile();

        $entityManager = $this->container->get('adaptive_entity_manager.test_entity_manager');

        // ValueObject support should be enabled by default
        $this->assertTrue($entityManager->hasValueObjectSupport());
        $this->assertInstanceOf(ValueObjectConverterRegistry::class, $entityManager->getValueObjectRegistry());
    }
}

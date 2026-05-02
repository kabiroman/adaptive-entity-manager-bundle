<?php

declare(strict_types=1);

namespace Kabiroman\AdaptiveEntityManagerBundle\Tests\Kernel;

use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AdaptiveEntityManagerBundle\Service\ManagerRegistryInterface;
use PHPUnit\Framework\TestCase;

final class AEMKernelTest extends TestCase
{
    public function testKernelBootsAndExposesAdaptiveEntityManager(): void
    {
        $kernel = new AEMKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        self::assertTrue($container->has('adaptive_entity_manager.default_entity_manager'));

        $em = $container->get('adaptive_entity_manager.default_entity_manager');
        self::assertInstanceOf(AdaptiveEntityManager::class, $em);

        self::assertTrue($container->has(ManagerRegistryInterface::class));
        $registry = $container->get(ManagerRegistryInterface::class);
        self::assertInstanceOf(ManagerRegistryInterface::class, $registry);

        $kernel->shutdown();
    }
}

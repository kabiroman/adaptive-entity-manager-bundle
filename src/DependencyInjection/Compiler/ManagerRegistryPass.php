<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ManagerRegistryPass implements CompilerPassInterface
{
    public const MANAGER_TAG = 'adaptive_entity_manager.entity_manager';
    
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('adaptive_entity_manager.manager_registry')) {
            return;
        }

        $definition = $container->getDefinition('adaptive_entity_manager.manager_registry');
        $taggedServices = $container->findTaggedServiceIds(self::MANAGER_TAG);

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addManager', [new Reference($id)]);
        }
    }
}

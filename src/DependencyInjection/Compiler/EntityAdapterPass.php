<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EntityAdapterPass implements CompilerPassInterface
{
    public const ADAPTER_TAG = 'adaptive_entity_manager.adapter';
    
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('adaptive_entity_manager.adapter_registry')) {
            return;
        }

        $definition = $container->getDefinition('adaptive_entity_manager.adapter_registry');
        $taggedServices = $container->findTaggedServiceIds(self::ADAPTER_TAG);

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addAdapter', [new Reference($id)]);
        }
    }
}

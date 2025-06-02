<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EntityManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('adaptive_entity_manager.data_adapter_entity_manager')) {
            throw new \RuntimeException('The adaptive_entity_manager.data_adapter_entity_manager parameter must be set');
        }

        $entityManagerId = $container->getParameter('adaptive_entity_manager.data_adapter_entity_manager');
        
        if (!$container->hasDefinition($entityManagerId)) {
            throw new \RuntimeException(sprintf('The entity manager service "%s" does not exist', $entityManagerId));
        }

        $container->getDefinition('adaptive_entity_manager.doctrine_manager')
            ->setSynthetic(true)
            ->setPublic(true);

        $container->register('adaptive_entity_manager.doctrine_manager.inner', 'Doctrine\ORM\EntityManagerInterface')
            ->setFactory([new Reference('service_container'), 'get'])
            ->setArguments([$entityManagerId])
            ->setPublic(false);
    }
}

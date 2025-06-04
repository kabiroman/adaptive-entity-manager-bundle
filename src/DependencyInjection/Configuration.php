<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('adaptive_entity_manager');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('entities_dir')
                    ->info('Directory where bundle entities will be stored - DEPRECATED')
                    ->setDeprecated('kabiroman/adaptive-entity-manager-bundle', '2.0', 'The "entities_dir" option is deprecated, use "entity_managers" instead.')
                    ->defaultValue('%kernel.project_dir%/src/Entity/AdaptiveManager')
                ->end()
                ->scalarNode('entities_namespace')
                    ->info('Namespace for bundle entities - DEPRECATED')
                    ->setDeprecated('kabiroman/adaptive-entity-manager-bundle', '2.0', 'The "entities_namespace" option is deprecated, use "entity_managers" instead.')
                    ->defaultValue('App\Entity\AdaptiveManager')
                ->end()
                ->arrayNode('entity_managers')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('entities_dir')
                                ->isRequired()
                                ->info('Directory for entities in this manager')
                            ->end()
                            ->scalarNode('entities_namespace')
                                ->isRequired()
                                ->info('Namespace for entities in this manager')
                            ->end()
                            ->scalarNode('connection')
                                ->info('Database connection name or configuration')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

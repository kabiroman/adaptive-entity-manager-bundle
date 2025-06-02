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
                    ->info('Directory where bundle entities will be stored')
                    ->defaultValue('%kernel.project_dir%/src/Entity/AdaptiveManager')
                ->end()
                ->scalarNode('entities_namespace')
                    ->info('Namespace for bundle entities')
                    ->defaultValue('App\Entity\AdaptiveManager')
                ->end()
            ->end();

        return $treeBuilder;
    }
}

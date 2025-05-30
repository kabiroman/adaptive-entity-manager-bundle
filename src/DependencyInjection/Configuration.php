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
                ->arrayNode('config')
                    ->isRequired()
                    ->children()
                        ->scalarNode('entity_folder')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('entity_namespace')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('cache_folder')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('entities')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('class')->isRequired()->end()
                            ->arrayNode('fields')
                                ->useAttributeAsKey('name')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('type')->isRequired()->end()
                                        ->booleanNode('id')->defaultFalse()->end()
                                        ->arrayNode('options')
                                            ->children()
                                                ->booleanNode('nullable')->defaultFalse()->end()
                                                ->integerNode('maxLength')->defaultNull()->end()
                                                ->integerNode('precision')->defaultNull()->end()
                                                ->integerNode('scale')->defaultNull()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

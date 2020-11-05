<?php

namespace DctT\TracabilityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface{
    
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('tracability');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('user_identifier')
                ->isRequired()
                ->cannotBeEmpty()
                ->end()
                ->arrayNode('actions')
                        ->children()
                            ->scalarNode('persist')->end()
                            ->scalarNode('update')->end()
                            ->scalarNode('remove')->end()
                        ->end()
                ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
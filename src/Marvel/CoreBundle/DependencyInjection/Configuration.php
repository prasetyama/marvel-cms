<?php

namespace Marvel\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface{
    public function getConfigTreeBuilder(){
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('marvel_core');

        $rootNode
            ->children()
                ->arrayNode('upload')
                    ->children()
                        ->scalarNode('logoDeveloper')
                            ->isRequired()
                        ->end()
                    ->end()
                    ->isRequired()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
?>

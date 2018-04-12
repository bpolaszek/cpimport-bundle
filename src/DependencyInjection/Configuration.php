<?php

namespace BenTools\CpImportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cpimport');

        $rootNode
            ->children()
                ->scalarNode('cpimport_bin')->defaultValue('/usr/local/mariadb/columnstore/bin/cpimport')->end()
                ->arrayNode('watch')->useAttributeAsKey('directory')->prototype('array')
                ->children()
                    ->scalarNode('directory')->end()
                    ->scalarNode('database')->isRequired()->end()
                    ->scalarNode('table')->isRequired()->end()
                    ->arrayNode('options')
                        ->children()
                        ->scalarNode('delimiter')->end()
                        ->scalarNode('enclosure')->end()
                        ->scalarNode('timeout')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}

<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for Boomgo bundle
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('plemi_boomgo');

        $rootNode
            ->children()
                ->scalarNode('default_connection')->defaultValue('default')->end()
            ->end()
            ->fixXmlConfig('connection')
            ->children()
                ->arrayNode('connections')
                    ->useAttributeAsKey('key')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->performNoDeepMerging()
                        ->children()
                            ->scalarNode('database')->isRequired()->end()
                            ->scalarNode('server')->end()
                            ->arrayNode('options')
                                ->performNoDeepMerging()
                                ->addDefaultsIfNotSet()
                                ->children()
                                // @see http://php.net/manual/fr/mongo.construct.php
                                    ->booleanNode('connect')->end()
                                    ->scalarNode('persist')->end()
                                    ->scalarNode('timeout')->end()
                                    ->scalarNode('replicaSet')->end()
                                    ->booleanNode('slaveOkay')->end()
                                    ->scalarNode('username')->end()
                                    ->scalarNode('password')->end()
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
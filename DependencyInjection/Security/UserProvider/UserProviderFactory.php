<?php

namespace Plemi\Bundle\BoomgoBundle\DependencyInjection\Security\UserProvider;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference;

/**
 * UserProviderFactory for Boomgo ODM.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class UserProviderFactory implements UserProviderFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config)
    {
        $container
            ->setDefinition($id, new DefinitionDecorator('plemi_boomgo.security.user.provider'))
            ->addArgument($config['class'])
            ->addArgument($config['property'])
        ;
    }

    public function getKey()
    {
        return 'mongodb';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('property')->defaultNull()->end()
            ->end()
        ;
    }
}

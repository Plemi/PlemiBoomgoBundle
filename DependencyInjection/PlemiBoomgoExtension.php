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

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Definition,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\FileLocator;

/**
 * PlemiBoomgoExtension services loader
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class PlemiBoomgoExtension extends Extension
{
    /**
     * {@inheritDoc}
     *
     * @param array            $configs   Configuration to load
     * @param ContainerBuilder $container Container of the bundle
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load services definitions
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('boomgo.xml');

        // Process multiples configurations
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, $configs);

        if (empty($config['connections'])) {
            throw new \InvalidArgumentException('You must define at least one connection in order to use "plemi_boomgo" service');
        }

        // Default connection
        $container->setParameter('plemi_boomgo.default_connection', $config['default_connection']);

        // Connections
        $connectionClass = $container->getParameter('plemi_boomgo.connection.class');

        foreach ($config['connections'] as $name => $parameters)
        {
            // Instanciate the definition class of the connection service
            $definition = new Definition($connectionClass, array(
                $parameters['database'],
                $parameters['server'],
                $parameters['options'],
            ));

            // Injection connection as a new service definition
            $identifier = sprintf('plemi_boomgo.%s_connection', $name);
            $container->setDefinition($identifier, $definition);

            // Get the newly injected connection and set into connection factory
            $container->getDefinition('plemi_boomgo.connection_factory')
                ->addMethodCall('addConnection', array(
                    $name,
                    new Reference($identifier)
                )
            );
        }
    }
}
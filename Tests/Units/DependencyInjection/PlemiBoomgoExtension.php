<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\DependencyInjection;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\DependencyInjection\PlemiBoomgoExtension as BaseExtension;

/**
 * Extension test
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class PlemiBoomgoExtension extends Test
{
    public function beforeTestMethod($method)
    {
        $this->mockClass('Symfony\\Component\\DependencyInjection\\ContainerBuilder');
    }

    public function afterTestMethod($method)
    {

    }

    public function testDefaultLoad()
    {
        $container = new \Mock\Symfony\Component\DependencyInjection\ContainerBuilder();

        $extension = new BaseExtension();

        $this->assert()
            ->exception(function() use ($extension, $container) {
                $extension->load(array(), $container);
            })
                ->isInstanceOf('InvalidArgumentException')
                ->hasMessage('You must define at least one connection in order to use "plemi_boomgo" service');
    }

    public function testCustomLoad()
    {
        $container = new \Mock\Symfony\Component\DependencyInjection\ContainerBuilder();

        $extension = new BaseExtension();

        $custom = array(
            'plemi_boomgo' =>array(
                'default_connection' => 'local',
                'connections' => array(
                    'local' => array(
                        'database' => 'myOrganization',
                        'server' => 'mongodb://localhost:27017',
                        'options' => array(
                            'replicaSet' => 'myReplicaSet'
                        )
                    ),
                    'remote' => array(
                        'database' => 'myOrganizationArchive',
                        'server' => 'myremote.domain.com'
                    )
                )
            )
        );

        $extension->load($custom, $container);

        $this->assert()
            ->boolean($container->hasParameter('plemi_boomgo.default_connection'))
                ->isTrue()
            ->string($container->getParameter('plemi_boomgo.default_connection'))
                ->isEqualTo('local');

        $this->assert()
            ->array($container->getDefinitions())
                ->hasSize(6)
                ->hasKeys(array(
                    'plemi_boomgo.manager',
                    'plemi_boomgo.connection_factory',
                    'plemi_boomgo.cache',
                    'plemi_boomgo.security.user.provider',
                    'plemi_boomgo.local_connection',
                    'plemi_boomgo.remote_connection'
                ));
    }
}
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

use Symfony\Component\Config\Definition\Processor;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\DependencyInjection\Configuration as BaseConfiguration;

/**
 * Configuration test
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class Configuration extends Test
{
    public function beforeTestMethod($method)
    {
        $this->processor = new Processor();
        $this->configuration = new BaseConfiguration();
    }

    public function afterTestMethod($method)
    {
        unset($this->processor, $this->configuration);
    }

    public function testDefault()
    {
        $options = $this->processor->processConfiguration($this->configuration, array());

        $this->assert()
            ->array($options)
                ->hasKeys(array('default_connection', 'connections'))
            ->string($options['default_connection'])
                ->isEqualTo('default')
            ->array($options['connections'])
                ->isEmpty();
    }

    public function testCustomized()
    {
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

        $options = $this->processor->processConfiguration($this->configuration, $custom);
        $this->assert()
            ->array($options['connections'])
                ->hasSize(2)
                ->hasKeys(array('local','remote'))
            ->array($options['connections']['local'])
                ->hasSize(3)
                ->hasKeys(array('database', 'server', 'options'))
            ->array($options['connections']['local']['options'])
                ->hasSize(1)
                ->hasKeys(array('replicaSet'))
            ->array($options['connections']['remote']['options'])
                ->isEmpty();
    }
}
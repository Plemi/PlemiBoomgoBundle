<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\Connection;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\Connection\ConnectionFactory as BaseConnectionFactory;

/**
 * ConnectionFactory tests
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class ConnectionFactory extends Test
{
    public function testDefaultConstruct()
    {
        $connectionFactory = new BaseConnectionFactory();

        $this->assert()
            ->string($connectionFactory->getDefaultConnectionName())
                ->isEmpty()
            ->array($connectionFactory->getAllConnections())
                ->isEmpty();
    }

    public function testCustomConstruct()
    {
        $this->mockClass('Plemi\Bundle\BoomgoBundle\Connection\Connection', 'Plemi\Bundle\BoomgoBundle\Connection', 'MockConnection');
        $localConnection = new \Plemi\Bundle\BoomgoBundle\Connection\MockConnection('myLocalDatabase');

        $connectionFactory = new BaseConnectionFactory('local', array('local' => $localConnection));

        $this->assert()
            ->string($connectionFactory->getDefaultConnectionName())
                ->isEqualTo('local')
            ->array($connectionFactory->getAllConnections())
                ->hasSize(1)
                ->hasKey('local')
                ->contains($localConnection);
    }

    public function testAccessorMutatorDefaultConnectionName()
    {
        $connectionFactory = new BaseConnectionFactory();

        $connectionFactory->setDefaultConnectionName('default');
        $this->assert()
            ->string($connectionFactory->getDefaultConnectionName())
                ->isEqualTo('default');
    }

    public function testAccessorMutatorConnectionFactory()
    {
        $connectionFactory = new BaseConnectionFactory();

        $this->assert()
            ->exception(function() use ($connectionFactory) {
                $connectionFactory->getConnection('remote');
            })
                ->isInstanceOf('InvalidArgumentException')
                ->hasMessage('Connection name "remote" does not exist in the list')
            ->exception(function() use ($connectionFactory) {
                $connectionFactory->removeConnection('remote');
            })
                ->isInstanceOf('InvalidArgumentException')
                ->hasMessage('Connection name "remote" does not exist in the list');

        $this->mockClass('Plemi\Bundle\BoomgoBundle\Connection\Connection', 'Plemi\Bundle\BoomgoBundle\Connection', 'MockConnection');
        $localConnection = new \Plemi\Bundle\BoomgoBundle\Connection\MockConnection('myLocalDatabase');

        $connectionFactory->addConnection('local', $localConnection);

        $this->assert()
            ->array($connectionFactory->getAllConnections())
                ->hasSize(1)
            ->object($connectionFactory->getConnection('local'))
                ->isEqualTo($localConnection);

        $remoteConnection = new \Plemi\Bundle\BoomgoBundle\Connection\MockConnection('myRemoteDatabase');
        $connectionFactory->addConnection('remote', $remoteConnection);

        $this->assert()
            ->array($connectionFactory->getAllConnections())
                ->hasSize(2)
            ->object($connectionFactory->getConnection('remote'))
                ->isEqualTo($remoteConnection);

        $connectionFactory->removeConnection('remote');

        $this->assert()
            ->array($connectionFactory->getAllConnections())
                ->hasSize(1);
    }
}
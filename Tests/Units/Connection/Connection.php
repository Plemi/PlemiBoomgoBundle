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
    Plemi\Bundle\BoomgoBundle\Connection\Connection as BaseConnection;

/**
 * Connection test
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class Connection extends Test
{
    public function testDefaultConstruct()
    {
        $connection = new BaseConnection('default');

        $this->assert()
            ->object($connection)
                ->isInstanceOf('Plemi\Bundle\BoomgoBundle\Connection\ConnectionInterface')
            ->string($connection->getDb())
                ->isEqualTo('default')
            ->string($connection->getServer())
                ->isEqualTo('mongodb://localhost:27017')
            ->array($connection->getOptions())
                ->isNotEmpty()
                ->hasKeys(array('connect'))
                ->containsValues(array(true));
    }

    public function testCustomizedConstruct()
    {
        // Customized state
        $connection = new BaseConnection(
            'my_organization',
            'mongodb:://bdd1.mydomain.com',
            array(
                'connect' => false,
                'persistent' => true
            )
        );

        $this->assert()
            ->string($connection->getDb())
                ->isEqualTo('my_organization')
            ->string($connection->getServer())
                ->isEqualTo('mongodb:://bdd1.mydomain.com')
            ->array($connection->getOptions())
                ->isNotEmpty()
                ->hasKeys(array('connect', 'persistent'))
                ->containsValues(array(false, true));
    }

    public function testSettersGetters()
    {
        $connection = new BaseConnection('default');

        $connection->setDb('my_organization');
        $this->assert()
            ->string($connection->getDb())
                ->isEqualTo('my_organization');

        $connection->setServer('mongodb:://bdd1.mydomain.com');
        $this->assert()
            ->string($connection->getServer())
                ->isEqualTo('mongodb:://bdd1.mydomain.com');

        $connection->setServer('mongodb:://bdd1.mydomain.com');
        $this->assert()
            ->string($connection->getServer())
                ->isEqualTo('mongodb:://bdd1.mydomain.com');

        $connection->setOptions(array(
            'connect' => false,
            'persistent' => true,
            'replicaSet' => 'myReplicaSet'
        ));
        $this->assert()
            ->array($connection->getOptions())
                ->hasKeys(array('connect', 'persistent', 'replicaSet'))
                ->containsValues(array(false, true, 'myReplicaSet'));
    }

    /**
     * As the private method "initialize" can't be mocked, using the "setOptions" method with
     * connect => false does the trick
     */
    public function testSettersGettersNative()
    {
        $connection = new BaseConnection('default');
        $connection->setOptions(array('connect' => false));

        $this->assert()
            ->object($connection->getMongoDB())
                ->isInstanceOf('MongoDB');

        $mongo = new \Mongo('mongodb://localhost:27017', array('connect' => false));
        $connection->setMongo($mongo);
        $this->assert()
            ->variable($connection->getMongo())
                ->isIdenticalTo($mongo);
    }
}
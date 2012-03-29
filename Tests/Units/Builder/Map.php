<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\Builder;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\Builder\Map as BaseMap;

/**
 * Map tests
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class Map extends Test
{
    public function testConstruct()
    {
        // All properties should be empty
        $map = new BaseMap('FQDN');

        $this->assert
            ->string($map->getType())
                ->isEmpty()
            ->string($map->getConnection())
                ->isEmpty()
            ->string($map->getCollection())
                ->isEmpty();
    }

    public function testMutatorsAndAccessorsType()
    {
        $map = new BaseMap('FQDN');

        // Should throw an exception
        $this->assert()
            ->exception(function() use ($map) {
                $map->setType('NESTED_DOCUMENT');
            })
                ->isInstanceOf('\InvalidArgumentException')
                ->hasMessage('Unrecognized document type "NESTED_DOCUMENT"');

        // Should return a valid document type
        $map->setType('EMBEDDED_DOCUMENT');

        $this->assert()
            ->string($map->getType())
                ->isEqualTo('EMBEDDED_DOCUMENT');
    }

    public function testMutatorsAndAccessorsConnection()
    {
        $map = new BaseMap('FQDN');
        $map->setConnection('my_local_connection');

        $this->assert()
            ->string($map->getConnection())
                ->isEqualTo('my_local_connection');
    }

    public function testMutatorsAndAccessorsCollection()
    {
        $map = new BaseMap('FQDN');
        $map->setCollection('fqdn_collection');

        $this->assert()
            ->string($map->getCollection())
                ->isEqualTo('fqdn_collection');
    }
}
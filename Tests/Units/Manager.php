<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\Manager as BaseManager;

/**
 * Manager test
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class Manager extends Test
{
    public function beforeTestMethod($method)
    {
        $this->mockClass('Plemi\\Bundle\\BoomgoBundle\\Connection\\ConnectionFactory', 'Plemi\\Bundle\\BoomgoBundle\\Connection', 'MockConnectionFactory');
        $this->mockConnectionFactory = new \Plemi\Bundle\BoomgoBundle\Connection\MockConnectionFactory();
    }

    public function afterTestMethod($method)
    {
        unset($this->mockConnectionFactory);
    }

    public function test__construct()
    {
        $manager = new BaseManager($this->mockConnectionFactory);

        $this->assert()
            ->object($manager)
                ->isInstanceOf('Plemi\\Bundle\\BoomgoBundle\\Manager');
    }

    public function testGetterSetterCache()
    {
        $manager = new BaseManager($this->mockConnectionFactory);

        // Default state should return null
        $this->assert()
            ->variable($manager->getCache())
                ->isNull();

        $this->mockClass('Boomgo\\Cache\\CacheInterface', 'Boomgo\\Cache', 'MockCache');
        $mockCache = new \Boomgo\Cache\MockCache();

        $manager->setCache($mockCache);

        // Should return a cache instance
        $this->assert()
            ->object($manager->getCache())
                ->isNotNull()
                ->isIdenticalTo($mockCache);
    }

    public function testGetterSetterConnectionFactory()
    {
        $manager = new BaseManager($this->mockConnectionFactory);

        $anotherMockConnectionFactory = $this->mockConnectionFactory;
        $manager->setConnectionFactory($anotherMockConnectionFactory);

        // Should return a connection factory instance
        $this->assert()
            ->object($manager->getConnectionFactory())
                ->isIdenticalTo($anotherMockConnectionFactory);
    }
}
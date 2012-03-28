<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle;

use Boomgo\Cache\CacheInterface;

use Plemi\Bundle\BoomgoBundle\Connection\ConnectionFactory;

/**
 * Manager for documents and repositories
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class Manager
{
    protected $connectionFactory;
    protected $cache;

    /**
     * Manager constructor
     * 
     * @param CacheInterface $cache The cache instance
     */
    public function __construct(ConnectionFactory $connectionFactory, CacheInterface $cache = null)
    {
        $this->connectionFactory = $connectionFactory;
        $this->cache = $cache;
    }

    /**
     * Define a connection factory instance
     * 
     * @param ConnectionFactory $connectionFactory The connection factory instance
     */
    public function setConnectionFactory(ConnectionFactory $connectionFactory)
    {
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * Return the connection factory instance
     * 
     * @return ConnectionFactory
     */
    public function getConnectionFactory()
    {
        return $this->connectionFactory;
    }

    /**
     * Define a cache instance for repositories
     * 
     * @param CacheInterface $cache The cache instance
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Return the cache instance if defined
     * 
     * @return mixed
     */
    public function getCache()
    {
        return $this->cache;
    }
}
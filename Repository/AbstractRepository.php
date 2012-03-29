<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Repository;

use Boomgo\Manager,
    Boomgo\Mapper\MapperProvider;

use Plemi\Bundle\BoomgoBundle\Connection\ConnectionFactory;

/**
 * AbstractRepository
 *
 * @author David Guyon <dguyon@gmail.com>
 */
abstract class AbstractRepository
{
    const OUTPUT_HYDRATED = 'HYDRATED';
    const OUTPUT_RAW = 'RAW';

    protected $connectionFactory;
    protected $mapper;
    protected $outputMode;
    protected $documentClassName;
    protected $connectionName;
    protected $collectionName;

    /**
     * AbstractRepository constructor
     * 
     * @param ConnectionFactory $connectionFactory Container of Connection instances
     * @param MapperProvider    $mapper            The documentClass mapper
     */
    public function __construct(ConnectionFactory $connectionFactory, MapperProvider $mapper)
    {
        $this->connectionFactory = $connectionFactory;
        $this->mapper = $mapper;
        $this->outputMode = self::OUTPUT_HYDRATED;
    }

    public function setConnectionFactory(ConnectionFactory $connectionFactory)
    {
        $this->connectionFactory = $connectionFactory;
    }

    public function getConnectionFactory()
    {
        return $this->connectionFactory;
    }

    public function setMapper(MapperProvider $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function getMongoCollection()
    {
        return $this->getConnectionFactory()
            ->getConnection($this->connectionName)
                ->getMongoDB()
                    ->selectCollection($this->collectionName);
    }
}
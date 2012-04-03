<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Builder;

use Boomgo\Builder\Map as BaseMap;

/**
 * Extended Map
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class Map extends BaseMap
{
    private $type;
    private $connection;
    private $collection;

    /**
     * {@inheritdoc}
     *
     * @param string $class The mapped FQDN
     */
    public function __construct($class)
    {
        parent::__construct($class);

        $this->type = '';
        $this->connection = '';
        $this->collection = '';
    }

    /**
     * Flag type for repository generation
     * 
     * @param string $type The document type
     * 
     * @throws InvalidArgumentException If unrecognized type
     */
    public function setType($type)
    {
        if (!in_array(strtoupper($type), array('DOCUMENT', 'EMBEDDED_DOCUMENT'))) {
            throw new \InvalidArgumentException(sprintf('Unrecognized document type "%s"', $type));
        }

        $this->type = strtoupper($type);
    }

    /**
     * Return document type
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Define the connection name for the document in order to associate to the matching Connection
     * 
     * @param string $connection The connection name
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return the connection name
     * 
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Define the collection name
     * 
     * @param string $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Return the collection name
     * 
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
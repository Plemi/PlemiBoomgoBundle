<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Connection;

/**
 * Connection class representing Mongo instance associated to a database
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class Connection implements ConnectionInterface
{
    protected $db;
    protected $server;
    protected $options;
    protected $mongo;

    /**
     * Connection constructor
     * 
     * @param string $db      The database name
     * @param string $server  The server DSN
     * @param array  $options Mongo connection options
     */
    public function __construct($db, $server = 'mongodb://localhost:27017', array $options = array('connect' => true))
    {
        $this->db = $db;
        $this->server = $server;
        $this->options = $options;
    }

    public function setMongo(\Mongo $mongo)
    {
        $this->mongo = $mongo;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setServer($server)
    {
        $this->server = $server;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Return defined options
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return a Mongo instance
     * 
     * @return \Mongo
     */
    public function getMongo()
    {
        if ($this->mongo == null) $this->initialize();

        return $this->mongo;
    }

    /**
     * Return a MongoDB instance
     * 
     * @return \MongoDB
     */
    public function getMongoDB()
    {
        return $this->getMongo()
            ->selectDB($this->db);
    }

    /**
     * Initialize a Mongo connection
     */
    private function initialize()
    {
        $this->mongo = new \Mongo($this->server, $this->options);
    }
}
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
 * ConnectionFactory acts as a Connection instances container
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class ConnectionFactory
{
    protected $defaultConnectionName;
    protected $connections;

    /**
     * ConnectionFactory constructor
     * 
     * @param array $connections Container of connection instances
     */
    public function __construct($defaultConnectionName = '', array $connections = array())
    {
        $this->defaultConnectionName = $defaultConnectionName;
        $this->connections = $connections;
    }

    /**
     * Set the default connection name
     *
     * @param string $name The connection name
     */
    public function setDefaultConnectionName($connectionName)
    {
        $this->defaultConnectionName = $connectionName;
    }

    /**
     * Returns the default connection name
     *
     * @return string The default connection name
     */
    public function getDefaultConnectionName()
    {
        return $this->defaultConnectionName;
    }

    /**
     * Define a new connection instance in the list
     * 
     * @param string              $name       The connection name
     * @param ConnectionInterface $connection The connection instance
     */
    public function addConnection($name, ConnectionInterface $connection)
    {
        $this->connections[$name] = $connection;
    }

    /**
     * Delete a connection instance if present
     * 
     * @param  string $name The connection name
     * 
     * @throws InvalidArgumentException If the connection doesn't exist in the list
     */
    public function removeConnection($name)
    {
        if (!isset($this->connections[$name])) {
            throw new \InvalidArgumentException(sprintf('Connection name "%s" does not exist in the list', $name));
        }

        unset($this->connections[$name]);
    }

    /**
     * Return a connection instance if present
     * 
     * @param  string $name The connection name
     * 
     * @throws InvalidArgumentException If the connection doesn't exist in the list
     * 
     * @return ConnectionInterface
     */
    public function getConnection($name)
    {
        if (!isset($this->connections[$name])) {
            throw new \InvalidArgumentException(sprintf('Connection name "%s" does not exist in the list', $name));
        }

        return $this->connections[$name];
    }

    /**
     * Return an array of Connection instances if defined or an empty array
     * 
     * @return array
     */
    public function getAllConnections()
    {
        return $this->connections;
    }
}
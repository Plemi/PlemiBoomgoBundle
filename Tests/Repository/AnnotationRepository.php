<?php

namespace Plemi\Bundle\BoomgoBundle\Tests\Repository;


use Boomgo\Mapper\MapperProvider;

use Plemi\Bundle\BoomgoBundle\Repository\AbstractRepository,
    Plemi\Bundle\BoomgoBundle\Connection\ConnectionFactory;

/**
 * AnnotationRepository
 *
 * Auto generated Repository class for/by Boomgo
 * Edit this file
 * Beware when versioning as Boomgo will rewrite it
 */
class AnnotationRepository extends AbstractRepository
{
    /**
     * AnnotationRepository constructor
     * 
     * {@inheritdoc}
     * 
     * @param ConnectionFactory $connectionFactory Container of Connection instances
     * @param MapperProvider    $mapper            The documentClass mapper
     */
    public function __construct(ConnectionFactory $connectionFactory, MapperProvider $mapper)
    {
        $this->documentClassName = 'Plemi\Bundle\BoomgoBundle\Tests\Document\Annotation';
        $this->connectionName = 'local_connection';
        $this->collectionName = 'my_collection';
        
        parent::__construct($connectionFactory, $mapper);
    }
}
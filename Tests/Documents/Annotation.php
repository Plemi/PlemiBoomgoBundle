<?php

namespace Plemi\Bundle\BoomgoBundle\Tests\Documents;

/**
 * Annotation class comment
 * 
 * @author John Doe <john@doe.com>
 * @Boomgo({"type": "Document", "connection": "local_connection", "collection": "my_collection"})
 */
class Annotation
{
    /**
     * @var type
     */
    public $nopersistent;

    /**
     * @Persistent
     */
    public $novar;

    /**
     * @Persistent
     * @var type short description
     */
    public $typeDescription;
}
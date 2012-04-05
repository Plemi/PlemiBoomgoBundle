<?php

namespace Plemi\Bundle\BoomgoBundle\Tests\Document;

/**
 * Annotation class comment
 * 
 * @author John Doe <john@doe.com>
 * @Boomgo({"type": "DOCUMENT", "connection": "local_connection", "collection": "my_collection"})
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
    protected $novar;

    /**
     * @Persistent
     * @var string short description
     */
    protected $typeDescription;

    /**
     * A single embedded DocumentEmbed
     *
     * @Persistent
     * @var Plemi\Bundle\BoomgoBundle\Tests\Document\EmbedAnnotation
     */
    protected $document;

    public function setNopersistent($value)
    {
        $this->nopersistent = $value;
    }

    public function getNopersistent()
    {
        return $this->nopersistent;
    }

    public function setNovar($value)
    {
        $this->novar = $value;
    }

    public function getNovar()
    {
        return $this->novar;
    }

    public function setTypeDescription($value)
    {
        $this->typeDescription = $value;
    }

    public function getTypeDescription()
    {
        return $this->typeDescription;
    }

    public function setDocument($value)
    {
        $this->document = $value;
    }

    public function getDocument()
    {
        return $this->document;
    }
}
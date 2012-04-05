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

use Boomgo\Formatter\FormatterInterface,
    Boomgo\Parser\ParserInterface,
    Boomgo\Builder\MapBuilder as BaseMapBuilder;

/**
 * Extended MapBuilder
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class MapBuilder extends BaseMapBuilder
{
    /**
     * @var array
     */
    protected $defaults;

    /**
     * Constructor defines the Parser & Formatter
     *
     * @param ParserInterface    $parser
     * @param FormatterInterface $formatter
     */
    public function __construct(ParserInterface $parser, FormatterInterface $formatter)
    {
        parent::__construct($parser, $formatter);

        $this->defaults = array(
            'type' => 'document',
            'connection' => 'default'
        );
    }

    /**
     * Define a new key/value pair into defaults metadata
     * 
     * @param string $key  The identifier key
     * @param mixed $value The associated valude
     */
    public function setDefaults($key, $value)
    {
        $this->defaults[$key] = $value;
    }

    /**
     * Return defaults metadatas
     * 
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $files List of files to parse
     *
     * @return array $processed
     */
    public function build(array $files)
    {
        $this->setMapClassName('Plemi\\Bundle\\BoomgoBundle\\Builder\\Map');

        $processed = array();

        foreach ($files as $file) {
            if ($this->parser->supports($file)) {

                $metadata = $this->parser->parse($file);
                $metadata = array_merge($this->getDefaults(), $metadata);

                $map = $this->buildMap($metadata);

                $processed[$map->getClass()] = $map;
            }
        }

        return $processed;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $metadata
     *
     * @return Map
     */
    protected function buildMap(array $metadata)
    {
        // Definitions processing
        $map = parent::buildMap($metadata);

        // Configuration processing
        $map->setType($metadata['type']);
        $map->setConnection($metadata['connection']);

        if (isset($metadata['collection'])) {
            $map->setCollection($metadata['collection']);
        } else {
            $collectionName = strtolower($map->getClassName());
            $map->setCollection($collectionName);
        }

        return $map;
    }
}
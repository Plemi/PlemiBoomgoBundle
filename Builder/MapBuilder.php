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

use Boomgo\Builder\MapBuilder as BaseMapBuilder;

/**
 * Extended MapBuilder
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class MapBuilder extends BaseMapBuilder
{
    /**
     * {@inheritdoc}
     *
     * @param array $files List of files to parse
     * @param array $inputs Injected data (ie: default_connection)
     *
     * @return array $processed
     */
    public function build(array $files, array $inputs = array())
    {
        $this->setMapClassName('Plemi\\Bundle\\BoomgoBundle\\Builder\\Map');

        $processed = array();

        $defaultMetadata = array(
            'type' => 'document',
            'connection' => 'default'
        );

        $defaultMetadata = array_merge($defaultMetadata, $inputs);

        foreach ($files as $file) {
            if ($this->parser->supports($file)) {

                $metadata = $this->parser->parse($file);
                $metadata = array_merge($defaultMetadata, $metadata);

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
<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\Builder;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\Builder\MapBuilder as BaseMapBuilder;

/**
 * MapBuilder tests
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class MapBuilder extends Test
{
    /**
     * Initialize Parser and Formatter dependencies for MapBuilder
     */
    public function beforeTestMethod($method)
    {
        $this->mockClass('Boomgo\\Parser\\ParserInterface', '\\Mock\\Parser', 'Parser');

        $this->mockClassParser = new \Mock\Parser\Parser();
        $this->mockClassParser->getMockController()->supports = function() {
            return true;
        };

        $this->mockClass('Boomgo\\Formatter\\FormatterInterface', '\\Mock\\Formatter', 'Formatter');

        $this->mockClassFormatter = new \Mock\Formatter\Formatter();
    }

    public function afterTestMethod($method)
    {
        unset($this->mockClassParser, $this->mockClassFormatter);
    }

    /**
     * Test various scenarios of parsed documents
     */
    public function testDefaultBuild()
    {
        $mapBuilder = new BaseMapBuilder($this->mockClassParser, $this->mockClassFormatter);

        // No global annotation
        $this->mockClassParser->getMockController()->parse = array(
            'class' => 'Plemi\\Bundle\\BoomgoBundle\\Tests\\Documents\\Annotation',
            'definitions' => array(
                array('attribute' => 'novar', 'type' => 'string'),
                array('attribute' => 'typeDescription', 'type' => 'string')
            )
        );

        $processedMaps = $mapBuilder->build(array(__DIR__.'/../../Documents/Annotation.php'));
        $annotationMap = $processedMaps['\Plemi\Bundle\BoomgoBundle\Tests\Documents\Annotation'];

        // Check valid processing
        $this->assert()
            ->array($processedMaps)
                ->isNotEmpty()
            ->object($annotationMap)
                ->isInstanceOf('Plemi\\Bundle\\BoomgoBundle\\Builder\\Map');

        $this->assert()
            ->string($annotationMap->getType())
                ->isEqualTo('DOCUMENT')
            ->string($annotationMap->getConnection())
                ->isEqualTo('default')
            ->string($annotationMap->getCollection())
                ->isEqualTo('annotation');

        // Global annotation defined within the document itself
        $this->mockClassParser->getMockController()->parse = array(
            'class' => 'Plemi\\Bundle\\BoomgoBundle\\Tests\\Documents\\Annotation',
            'type' => 'EMBEDDED_DOCUMENT',
            'collection' => 'my_collection',
            'connection' => 'local_connection',
            'definitions' => array(
                array('attribute' => 'novar', 'type' => 'string'),
                array('attribute' => 'typeDescription', 'type' => 'string')
            )
        );

        $processedMaps = $mapBuilder->build(array(__DIR__.'/../../Documents/Annotation.php'));
        $annotationMap = $processedMaps['\Plemi\Bundle\BoomgoBundle\Tests\Documents\Annotation'];

        $this->assert()
            ->string($annotationMap->getType())
                ->isEqualTo('EMBEDDED_DOCUMENT')
            ->string($annotationMap->getConnection())
                ->isEqualTo('local_connection')
            ->string($annotationMap->getCollection())
                ->isEqualTo('my_collection');
    }

    /**
     * Test build with injected data like default connection
     */
    public function testCustomBuild()
    {
        $mapBuilder = new BaseMapBuilder($this->mockClassParser, $this->mockClassFormatter);

        // No global annotation
        $this->mockClassParser->getMockController()->parse = array(
            'class' => 'Plemi\\Bundle\\BoomgoBundle\\Tests\\Documents\\Annotation',
            'definitions' => array(
                array('attribute' => 'novar', 'type' => 'string'),
                array('attribute' => 'typeDescription', 'type' => 'string')
            )
        );

        $processedMaps = $mapBuilder->build(array(__DIR__.'/../../Documents/Annotation.php'), array('connection' => 'injected_connection'));
        $annotationMap = $processedMaps['\Plemi\Bundle\BoomgoBundle\Tests\Documents\Annotation'];

        $this->assert()
            ->string($annotationMap->getConnection())
                ->isEqualTo('injected_connection');

        // Global annotation defined within the document itself
        $this->mockClassParser->getMockController()->parse = array(
            'class' => 'Plemi\\Bundle\\BoomgoBundle\\Tests\\Documents\\Annotation',
            'connection' => 'local_connection',
            'definitions' => array(
                array('attribute' => 'novar', 'type' => 'string'),
                array('attribute' => 'typeDescription', 'type' => 'string')
            )
        );

        $processedMaps = $mapBuilder->build(array(__DIR__.'/../../Documents/Annotation.php'), array('connection' => 'injected_connection'));
        $annotationMap = $processedMaps['\Plemi\Bundle\BoomgoBundle\Tests\Documents\Annotation'];

        $this->assert()
            ->string($annotationMap->getConnection())
                ->isEqualTo('local_connection');
    }
}
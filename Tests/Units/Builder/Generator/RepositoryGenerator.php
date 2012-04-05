<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\Builder\Generator;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\Builder\Generator\RepositoryGenerator as BaseRepositoryGenerator;

/**
 * RepositoryGenerator tests
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class RepositoryGenerator extends Test
{
    public function beforeTestMethod($method)
    {
        $this->mockClass('Boomgo\\Parser\\ParserInterface', '\\Mock\\Parser', 'Parser');
        $this->mockClass('Boomgo\\Formatter\\FormatterInterface', '\\Mock\\Formatter', 'Formatter');
        $this->mockClass('Plemi\\Bundle\\BoomgoBundle\\Builder\\Map', '\\Mock\\Builder', 'Map');
        $this->mockClass('Plemi\\Bundle\\BoomgoBundle\\Builder\\MapBuilder', '\\Mock\\Builder', 'MapBuilder');
        $this->mockClass('TwigGenerator\\Builder\\Generator', '\\Mock\\Builder', 'TwigGenerator');

        $mockClassParser = new \Mock\Parser\Parser();
        $mockClassFormatter = new \Mock\Formatter\Formatter();

        // Avoid constructor call for Map
        $mockControllerMap = new \mageekguy\atoum\mock\controller();
        $mockControllerMap->__construct = function() {};
        $mockControllerMap->getClass = function() {
            return 'Plemi\\Bundle\\BoomgoBundle\\Tests\\Document\\Annotation';
        };
        $mockControllerMap->getClassName = function() {
            return 'Annotation';
        };
        $mockControllerMap->getNamespace = function() {
            return 'Plemi\\Bundle\\BoomgoBundle\\Tests\\Document';
        };
        $mockControllerMap->getType = function() {
            return 'DOCUMENT';
        };

        $mockClassMap = new \Mock\Builder\Map($mockControllerMap);

        $this->mockClassMapBuilder = new \Mock\Builder\MapBuilder($mockClassParser, $mockClassFormatter);
        // Rewrite and inject mock map
        $this->mockClassMapBuilder->getMockController()->build = function() use ($mockClassMap) {
            return array($mockClassMap);
        };

        // Avoid constructor call for TwigGenerator with creating directory
        $mockControllerTwigGenerator = new \mageekguy\atoum\mock\controller();
        $mockControllerTwigGenerator->__construct = function() {};
        $mockControllerTwigGenerator->writeOndisk = function() {};

        $this->mockClassTwigGenerator = new \Mock\Builder\TwigGenerator($mockControllerTwigGenerator);
    }

    public function afterTestMethod($method)
    {
        unset($this->mockClassMapBuilder, $this->mockClassTwigGenerator);
    }

    public function testUnitGenerate()
    {
        $generator = new BaseRepositoryGenerator($this->mockClassMapBuilder, $this->mockClassTwigGenerator);

        $this->assert
            ->exception(function () use ($generator) {
                $generator->generate(
                    __DIR__.'/../../../Document/Annotation.php',
                    'Annotation',
                    'Mapper',
                    __DIR__.'/../../../',
                    'Repositorie'
                );
            })
            ->isInstanceOf('RuntimeException')
            ->hasMessage('The Document map "Plemi\Bundle\BoomgoBundle\Tests\Document\Annotation" doesn\'t include the document base namespace "Annotation"');

        $this->assert
            ->boolean($generator->generate(
                array(__DIR__.'/../../../Document/Annotation.php'),
                'Document',
                'Mapper',
                __DIR__.'/../../../',
                'Repositorie'
            ))
                ->isTrue();
    }

    // public function testFunctionalGenerate()
    // {
    //     $parser = new \Plemi\Bundle\BoomgoBundle\Parser\AnnotationParser();
    //     $formatter = new \Boomgo\Formatter\CamelCaseFormatter();
    //     $mapBuilder = new \Plemi\Bundle\BoomgoBundle\Builder\MapBuilder($parser, $formatter);
    //     $twigGenerator = new \TwigGenerator\Builder\Generator();
    //     $generator = new BaseRepositoryGenerator($mapBuilder, $twigGenerator);

    //     $generator->generate(
    //         __DIR__.'/../../../Documents/',
    //         'Documents',
    //         'Mappers',
    //         __DIR__.'/../../../Documents',
    //         'Repositories'
    //     );
    // }
}
<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\Parser;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\Parser\AnnotationParser as BaseAnnotationParser;

/**
 * AnnotationParser tests
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class AnnotationParser extends Test
{
    /**
     * Testing private 'isBoomgoClass' method with ReflectionMethod class with scenarios :
     * 
     * - no comment
     * - comment without Boomgo global annotation
     * - 2 occurences of Boomgo global annotation
     * - valid Boomgo global annotation
     */
    public function testIsBoomgoClass()
    {
        $parser = new BaseAnnotationParser();
        $parserReflectedMethod = new \ReflectionMethod('Plemi\Bundle\BoomgoBundle\Parser\AnnotationParser', 'isBoomgoClass');
        $parserReflectedMethod->setAccessible(true);

        // Mocking getDocComment in order to set different use case with an existing class
        $this->mockClass('\ReflectionClass');

        $atoumController = new \mageekguy\atoum\mock\controller();
        $atoumController->__construct = function() {};

        $mockReflectedClass = new \mock\ReflectionClass($atoumController);

        // Should return false
        $mockReflectedClass->getMockController()->getDocComment = false;
        $this->assert()
            ->boolean($parserReflectedMethod->invoke($parser, $mockReflectedClass))
                ->isFalse();

        // Should return false too
        $docComment =
<<<EOF
/**
 * Sample comment
 * @author John Doe <john@doe.com>
 */
EOF;
        $mockReflectedClass->getMockController()->getDocComment = $docComment;
        $this->assert()
            ->boolean($parserReflectedMethod->invoke($parser, $mockReflectedClass))
                ->isFalse();

        // Should throw an exception
        $docComment =
<<<EOF
/**
 * Sample comment
 * @author John Doe <john@doe.com>
 * @Boomgo({"connection": "local_connection"});
 * @Boomgo({"collection": "my_collection"});
 */
EOF;
        $mockReflectedClass->getMockController()->getDocComment = $docComment;
        $this->assert()
            ->exception(function() use ($parserReflectedMethod, $parser, $mockReflectedClass) {
                $parserReflectedMethod->invoke($parser, $mockReflectedClass);
            })
                ->isInstanceOf('\RuntimeException')
                ->hasMessage('Boomgo class annotation tag should occur only once for "mageekguy\atoum\mock\controller"');

        // Should return true
        $docComment =
<<<EOF
/**
 * Sample comment
 * @author John Doe <john@doe.com>
 * @Boomgo({"connection": "local_connection", "collection": "my_collection"});
 */
EOF;
        $mockReflectedClass->getMockController()->getDocComment = $docComment;
        $this->assert()
            ->boolean($parserReflectedMethod->invoke($parser, $mockReflectedClass))
                ->isTrue();
    }

    /**
     * Testing private 'parseMetadataClass' method with ReflectionMethod class with scenarios :
     * 
     * - comment without Boomgo global annotation
     * - invalid json string within Boomgo global annotation
     * - valid Boomgo global annotation
     */
    public function testParseMetadataClass()
    {
        $parser = new BaseAnnotationParser();
        $parserReflectedMethod = new \ReflectionMethod('Plemi\Bundle\BoomgoBundle\Parser\AnnotationParser', 'parseMetadataClass');
        $parserReflectedMethod->setAccessible(true);

        // Mocking getDocComment in order to set different use case with an existing class
        $this->mockClass('\ReflectionClass');

        $atoumController = new \mageekguy\atoum\mock\controller();
        $atoumController->__construct = function() {};

        $mockReflectedClass = new \mock\ReflectionClass($atoumController);

        // Should return an empty array
        $docComment =
<<<EOF
/**
 * Sample comment
 * @author John Doe <john@doe.com>
 */
EOF;

        $mockReflectedClass->getMockController()->getDocComment = $docComment;
        $this->assert()
            ->array($parserReflectedMethod->invoke($parser, $mockReflectedClass))
                ->isEmpty();

        // Should throw an exception
        $docComment =
<<<EOF
/**
 * Sample comment
 * @author John Doe <john@doe.com>
 * @Boomgo({connection: "my_invalid_connection"});
 */
EOF;

        $mockReflectedClass->getMockController()->getDocComment = $docComment;
        $this->assert()
            ->exception(function() use ($parserReflectedMethod, $parser, $mockReflectedClass) {
                $parserReflectedMethod->invoke($parser, $mockReflectedClass);
            })
                ->isInstanceOf('\InvalidArgumentException')
                ->hasMessage('Invalid json string found for class "mageekguy\atoum\mock\controller"');

        // Should return an array with connection and collection keys
        $docComment =
<<<EOF
/**
 * Sample comment
 * @author John Doe <john@doe.com>
 * @Boomgo({"connection": "local_connection", "collection": "my_collection"});
 */
EOF;

        $mockReflectedClass->getMockController()->getDocComment = $docComment;
        $this->assert()
            ->array($parserReflectedMethod->invoke($parser, $mockReflectedClass))
                ->isNotEmpty()
                ->hasKeys(array('connection', 'collection'))
                ->containsValues(array('local_connection', 'my_collection'));
    }

    /**
     * Test the complete process of global parsing with a valid document
     */
    public function testProcessClassParsing()
    {
        $parser = new BaseAnnotationParser();
        $documentReflectedClass = new \ReflectionClass('Plemi\Bundle\BoomgoBundle\Tests\Documents\Annotation');

        $parserReflectedMethod = new \ReflectionMethod('Plemi\Bundle\BoomgoBundle\Parser\AnnotationParser', 'processClassParsing');
        $parserReflectedMethod->setAccessible(true);

        $metadata = $parserReflectedMethod->invoke($parser, $documentReflectedClass);

        $this->assert()
            ->array($metadata)
                ->hasSize(3)
                ->hasKeys(array('type', 'connection', 'collection'))
            ->string($metadata['type'])
                ->isEqualTo('Document')
            ->string($metadata['connection'])
                ->isEqualTo('local_connection')
            ->string($metadata['collection'])
                ->isEqualTo('my_collection');
    }

    public function testParse()
    {
        $parser = new BaseAnnotationParser();
        $metadata = $parser->parse(__DIR__.'/../../Documents/Annotation.php');

        $this->assert
            ->array($metadata)
                ->hasSize(5)
                ->hasKeys(array('class','type', 'connection', 'collection', 'definitions'))
            ->string($metadata['class'])
                ->isEqualTo('Plemi\\Bundle\\BoomgoBundle\\Tests\\Documents\\Annotation')
            ->string($metadata['type'])
                ->isEqualTo('Document')
            ->string($metadata['connection'])
                ->isEqualTo('local_connection')
            ->string($metadata['collection'])
                ->isEqualTo('my_collection');
    }
}
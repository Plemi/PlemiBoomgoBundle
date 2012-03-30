<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Parser;

use Boomgo\Parser\AnnotationParser as BaseAnnotationParser;

/**
 * AnnotationParser
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class AnnotationParser extends BaseAnnotationParser
{
    /**
     * {@inheritdoc}
     *
     * @param string $filepath
     *
     * @return array
     */
    public function parse($filepath)
    {
        $metadata = array();

        $reflectedClass = $this->getReflection($filepath);
        $metadata['class'] = $reflectedClass->getName();
        
        $classMetadata = $this->processClassParsing($reflectedClass);
        $propertiesMetadata = $this->processPropertiesParsing($reflectedClass);

        $metadata = array_merge($metadata, $classMetadata, $propertiesMetadata);

        return $metadata;
    }

    /**
     * Execute parsing process of class comment
     * 
     * @param  \ReflectionClass $reflectedClass The document ReflectedClass
     * 
     * @return array An array filled with class metadata
     */
    protected function processClassParsing(\ReflectionClass $reflectedClass)
    {
        $metadataClass = array();

        if ($this->isBoomgoClass($reflectedClass)) {
            $metadataClass = $this->parseMetadataClass($reflectedClass);
        }

        return $metadataClass;
    }

    /**
     * Check if a class comment contains a valid Boomgo global annotation
     * 
     * @param  \ReflectionClass $reflectedClass The class to check
     * 
     * @throws RuntimeException If contains more than one valid Boomgo annotation
     * 
     * @return boolean True if the DocComment can be processeed by Boomgo
     */
    private function isBoomgoClass(\ReflectionClass $reflectedClass)
    {
        if (false === $reflectedClass->getDocComment()) return false;

        $annotationTag = substr_count($reflectedClass->getDocComment(), $this->getGlobalAnnotation());

        if (0 < $annotationTag) {
            if (1 === $annotationTag) {
                return true;
            }

            throw new \RuntimeException(sprintf('Boomgo class annotation tag should occur only once for "%s"', $reflectedClass->getName()));
        }

        return false;
    }

    /**
     * Parse class comment to extract document type, connection and collection if defined
     * 
     * @param  \ReflectionClass $reflectedClass The class to parse comment
     * 
     * @throws \InvalidArgumentException If invalid json string found
     * 
     * @return array
     */
    private function parseMetadataClass(\ReflectionClass $reflectedClass)
    {
        $docComment = $reflectedClass->getDocComment();
        $className = $reflectedClass->getName();
        $metadata = array();

        // Grep everything between parenthesis following Boomgo global annotation
        preg_match('#@Boomgo\(([^)]+)\)#', $docComment, $captured);

        if (isset($captured[1])) {
            if (true == ($config = json_decode($captured[1], true))) {
                foreach ($config as $key => $value) {
                    if (in_array($key, array('type', 'connection', 'collection'))) $metadata[$key] = $value;
                }
            } else {
                throw new \InvalidArgumentException(sprintf('Invalid json string found for class "%s"', $className));
            }
        }

        return $metadata;
    }
}
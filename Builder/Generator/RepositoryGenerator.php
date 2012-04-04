<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Builder\Generator;

use Boomgo\Builder\Generator\AbstractGenerator;

use Plemi\Bundle\BoomgoBundle\Builder\TwigRepositoryBuilder;

/**
 * RepositoryGenerator
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class RepositoryGenerator extends AbstractGenerator
{
    /**
     * Initialize default instance state
     */
    protected function initialize()
    {
        $this->getTwigGenerator()->setVariables(array(
            'extends' => 'AbstractRepository'
        ));
    }

    /**
     * Generate repositories
     *
     * The base models & mappers namespace are just the "namespace fragment"
     * not the full namespace part, i.e. "Document", "Mapper"
     * -"Document" & "Mapper": Project\Domain\Document => Project\Domain\Mapper
     * -"Document" & "Document\Mapper": Project\Domain\Document => Project\Domain\Document\Mapper
     *
     * The Base models directory & base models namespace must match PSR-O
     * This means: base models namespace fragment must match the end of your base model directory
     * - "Document" => "/path/to/your/Project/Document"
     * - "Domain\SubDomain\Model" => "/path/to/your/Domain/SubDomain/Model"
     *
     * The generator will write aside of your Document folder/namespace. If you want to change this
     * behavior, you just have to customize the base mapper namespace: "Document\Mapper"
     * 
     * @param string $sources              Mapping source directory
     * @param string $baseModelsNamespace  Base models namespace (Document, Model)
     * @param string $baseMappersNamespace Base mappers namespace (Mapper, Mapping)
     * @param string $baseModelsDirectory  Base models directory
     * @param string $baseRepositoriesNamespace Base repositories namespace (Repository, Repos)
     * 
     * @return boolean If generation process succeeded
     */
    public function generate($sources, $baseModelsNamespace = 'Document', $baseMappersNamespace = 'Mapper', $baseModelsDirectory = '', $baseRepositoriesNamespace = 'Repository')
    {
        // Explicit call for TwigRepositoryGenerator requirements
        $this->initialize();

        $baseModelsNamespace = trim($baseModelsNamespace, '\\');
        $baseMappersNamespace = trim($baseMappersNamespace, '\\');
        $baseRepositoriesNamespace = trim($baseRepositoriesNamespace, '\\');
        $baseModelsDirectory = rtrim($baseModelsDirectory, DIRECTORY_SEPARATOR);

        $part = str_replace('\\', DIRECTORY_SEPARATOR, $baseModelsNamespace);

        $files = $this->load($sources, '.'.$this->getMapBuilder()->getParser()->getExtension());
        $maps = $this->getMapBuilder()->build($files);

        // Only process if map type is a Document and not an embedded one
        foreach ($maps as $map) {

            if ($map->getType() !== 'DOCUMENT') {
                continue;
            }

            // Models informations
            $modelClassName = $map->getClassName();
            $modelNamespace = trim($map->getNamespace(), '\\');

            // Check if model namespace is contained within baseModelsNamespace
            if (substr_count($modelNamespace, $baseModelsNamespace) == 0) {
                throw new \RuntimeException(sprintf('The Document map "%s" doesn\'t include the document base namespace "%s"', $map->getClass(), $baseModelsNamespace));
            }

            $modelExtraNamespace = str_replace($baseModelsNamespace, '', strstr($modelNamespace, $baseModelsNamespace));

            // Mapper informations
            $mapperDirectory = str_replace(str_replace('\\', DIRECTORY_SEPARATOR, $baseModelsNamespace), str_replace('\\', DIRECTORY_SEPARATOR, $baseMappersNamespace), $baseModelsDirectory.$modelExtraNamespace);
            $mapperClassName = $modelClassName.'Mapper';
            $mapperFileName = $mapperClassName.'.php';
            $mapperNamespace = str_replace($baseModelsNamespace, $baseMappersNamespace, $modelNamespace);

            // Repository informations
            $repositoryDirectory = str_replace(str_replace('\\', DIRECTORY_SEPARATOR, $baseModelsNamespace), str_replace('\\', DIRECTORY_SEPARATOR, $baseRepositoriesNamespace), str_replace('\\', DIRECTORY_SEPARATOR, $baseModelsDirectory.$modelExtraNamespace));
            $repositoryClassName = $modelClassName.'Repository';
            $repositoryFileName = $repositoryClassName.'.php';
            $repositoryNamespace = str_replace($baseModelsNamespace, $baseRepositoriesNamespace, $modelNamespace);

            // Custom Twig builder
            $twigRepositoryBuilder = new TwigRepositoryBuilder();
            $this->getTwigGenerator()->addBuilder($twigRepositoryBuilder);

            $twigRepositoryBuilder->setOutputName($repositoryFileName);
            $twigRepositoryBuilder->addTemplateDir(__DIR__.'/../Templates');
            $twigRepositoryBuilder->setVariable('modelNamespace', $modelNamespace);
            $twigRepositoryBuilder->setVariable('modelClassName', $modelClassName);
            $twigRepositoryBuilder->setVariable('mapperNamespace', $mapperNamespace);
            $twigRepositoryBuilder->setVariable('mapperClassName', $mapperClassName);
            $twigRepositoryBuilder->setVariable('repositoryNamespace', $repositoryNamespace);
            $twigRepositoryBuilder->setVariable('repositoryClassName', $repositoryClassName);
            $twigRepositoryBuilder->setVariable('map', $map);

            $this->getTwigGenerator()->writeOnDisk($repositoryDirectory);
        }

        return true;
    }
}
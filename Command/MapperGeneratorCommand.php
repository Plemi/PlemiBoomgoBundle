<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;

use TwigGenerator\Builder\Generator as TwigGenerator;

use Boomgo\Parser\AnnotationParser,
    Boomgo\Formatter\CamelCaseFormatter,
    Boomgo\Builder\MapBuilder,
    Boomgo\Builder\Generator\MapperGenerator;

/**
 * Mapper Generator Command
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class MapperGeneratorCommand extends BoomgoCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('boomgo:generate:mappers')
            ->setDescription('Generate mapper classes from map.')
            ->addArgument('bundle', InputArgument::REQUIRED, 'The bundle name to create mapper classes in.')
            ->setHelp(<<<EOF
The <info>boomgo:generate:mappers</info> command generates mapper classes from a map containing metadata defined in your document classes.
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * 
     * @todo Prepare for other parser (YAML)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundleName = $input->getArgument('bundle');

        // Command requirements
        $bundleInstance = $this->getBundleInstance($bundleName);
        $bundleFullPath = $bundleInstance->getPath();

        // Generation process
        $parser = new AnnotationParser();
        $formatter = new CamelCaseFormatter();

        $mapBuilder = new MapBuilder($parser, $formatter);
        $twigGenerator = new TwigGenerator();

        $generator = new MapperGenerator($mapBuilder, $twigGenerator);

        $generator->generate($bundleFullPath.'/Document', 'Document', 'Mapper', $bundleFullPath.'/Document');

        $output->writeln('<info>Mappers have been generated</info>');
    }
}
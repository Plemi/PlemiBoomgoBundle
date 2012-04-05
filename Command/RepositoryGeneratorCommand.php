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

use Boomgo\Formatter\CamelCaseFormatter;

use Plemi\Bundle\BoomgoBundle\Parser\AnnotationParser,
    Plemi\Bundle\BoomgoBundle\Builder\MapBuilder,
    Plemi\Bundle\BoomgoBundle\Builder\Generator\RepositoryGenerator;

/**
 * Repository Generator Command
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class RepositoryGeneratorCommand extends BoomgoCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('boomgo:generate:repositories')
            ->setDescription('Generate repository classes from map.')
            ->addArgument('bundle', InputArgument::REQUIRED, 'The bundle name to create repository classes in.')
            ->setHelp(<<<EOF
The <info>boomgo:generate:repositories</info> command generates repository classes from a map containing metadata defined in your document classes.
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

        // Injection and generation process
        $boomgo = $this->getApplication()->getKernel()->getContainer()->get('plemi_boomgo.manager');
        $defaultConnection = $boomgo->getConnectionFactory()->getDefaultConnectionName();

        $parser = new AnnotationParser();
        $formatter = new CamelCaseFormatter();

        $mapBuilder = new MapBuilder($parser, $formatter);
        $mapBuilder->setDefaults('connection', $defaultConnection);

        $twigGenerator = new TwigGenerator();

        $generator = new RepositoryGenerator($mapBuilder, $twigGenerator);

        $generator->generate($bundleFullPath.'/Document', 'Document', 'Mapper', $bundleFullPath.'/Document', 'Repository');

        $output->writeln('<info>Repositories have been generated</info>');
    }
}
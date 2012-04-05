<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\Command;

use Symfony\Component\Console\Application,
    Symfony\Component\Console\Tester\CommandTester;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test,
    Plemi\Bundle\BoomgoBundle\Command\RepositoryGeneratorCommand as BaseRepositoryGeneratorCommand;

/**
 * RepositoryGeneratorCommand tests
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class RepositoryGeneratorCommand extends Test
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new BaseRepositoryGeneratorCommand());

        $command = $application->find('boomgo:generate:repositories');

        $commandTester = new CommandTester($command);

        // No arguments
        $this->assert()
            ->exception(function() use ($commandTester, $command) {
                $commandTester->execute(array('command' => $command->getName()));
            })
                ->isInstanceOf('RuntimeException')
                ->hasMessage('Not enough arguments.');
    }
}
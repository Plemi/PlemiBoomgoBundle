<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units\Repository;

use Plemi\Bundle\BoomgoBundle\Tests\Units\Test;

/**
 * AbstractRepository test
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
class AbstractRepository extends Test
{
    public function beforeTestMethod($method)
    {
        $this->mockClass('Plemi\Bundle\BoomgoBundle\Repository\AbstractRepository');
        $this->mockClass('Plemi\Bundle\BoomgoBundle\Connection\ConnectionFactory');
        $this->mockClass('Boomgo\Mapper\MapperProvider');

        $this->mockConnectionFactory = new \Mock\Plemi\Bundle\BoomgoBundle\Connection\ConnectionFactory();
        $this->mockMapperProvider = new \Mock\Boomgo\Mapper\MapperProvider();

        if ($method == 'testMutatorsAndAccessors') {
            $this->repository = new \Mock\Plemi\Bundle\BoomgoBundle\Repository\AbstractRepository($this->mockConnectionFactory, $this->mockMapperProvider);
        }
    }

    public function afterTestMethod($method)
    {
        unset($this->mockConnectionFactory, $this->mockMapperProvider);

        if ($method == 'testMutatorsAndAccessors') unset($this->repository);
    }

    public function testConstruct()
    {
        $repository = new \Mock\Plemi\Bundle\BoomgoBundle\Repository\AbstractRepository($this->mockConnectionFactory, $this->mockMapperProvider);
        $this->assert()
            ->object($repository->getConnectionFactory())
                ->isEqualTo($this->mockConnectionFactory)
            ->object($repository->getMapper())
                ->isEqualTo($this->mockMapperProvider);
    }

    public function testMutatorsAndAccessors()
    {
        $anotherMockConnectionFactory = new \Mock\Plemi\Bundle\BoomgoBundle\Connection\ConnectionFactory();
        $anotherMockMapperProvider = new \Mock\Boomgo\Mapper\MapperProvider();

        $this->repository->setConnectionFactory($anotherMockConnectionFactory);
        $this->assert()
            ->object($this->repository->getConnectionFactory())
                ->isEqualTo($anotherMockConnectionFactory);

        $this->repository->setMapper($anotherMockMapperProvider);
        $this->assert()
            ->object($this->repository->getMapper())
                ->isEqualTo($anotherMockMapperProvider);
    }
}
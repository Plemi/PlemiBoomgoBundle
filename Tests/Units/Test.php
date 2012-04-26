<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Tests\Units;

use mageekguy\atoum,
    mageekguy\atoum\factory;

/**
 * Define namespace for unit test
 * 
 * @author David Guyon <dguyon@gmail.com>
 */
abstract class Test extends atoum\test
{
    /**
     * {@inheritdoc}
     */
    public function __construct(factory $factory = null)
    {
        $this->setTestNamespace('\\Tests\\Units');
        parent::__construct($factory);
    }
}
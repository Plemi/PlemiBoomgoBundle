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

use mageekguy\atoum;

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
    public function __construct(score $score = null, locale $locale = null, adapter $adapter = null)
    {
        $this->setTestNamespace('\\Tests\\Units');
        parent::__construct($score, $locale, $adapter);
    }
}
<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle\Builder;

use TwigGenerator\Builder\BaseBuilder;

/**
 * TwigRepositoryBuilder
 *
 * @author David Guyon <dguyon@gmail.com>
 */
class TwigRepositoryBuilder extends BaseBuilder
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getDefaultTemplateName()
    {
        return 'Repository'.self::TWIG_EXTENSION;
    }
}
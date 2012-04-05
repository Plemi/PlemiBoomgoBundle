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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Bundle\FrameworkBundle\Console\Application;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Common presets for Boomgo commands
 *
 * @author David Guyon <dguyon@gmail.com>
 */
abstract class BoomgoCommand extends ContainerAwareCommand
{
    /**
     * Search within registered bundle list and return instance if found
     * 
     * @param  string $bundleName Bundle name to look for
     * 
     * @return mixed Bundle object if bundle found, false otherwise
     */
    public function getBundleInstance($bundleName)
    {
        $instance = false;

        foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
            if (strtolower($bundleName) == strtolower($bundle->getName())) {
                $instance = $bundle;
                break;
            }
        }

        if (false == $instance) {
            throw new \InvalidArgumentException('Bundle name "'.$bundleName.'" isn\'t registered into Kernel.');
        }

        return $instance;
    }
}
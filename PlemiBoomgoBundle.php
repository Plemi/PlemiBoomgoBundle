<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plemi\Bundle\BoomgoBundle;

use Plemi\Bundle\BoomgoBundle\DependencyInjection\PlemiBoomgoExtension,
    Plemi\Bundle\BoomgoBundle\DependencyInjection\Security\UserProvider\UserProviderFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * PlemiBoomgoBundle
 *
 * @author David Guyon <dguyon@gmail.com>
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class PlemiBoomgoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if ($container->hasExtension('security')) {
            $container->getExtension('security')->addUserProviderFactory(new UserProviderFactory('mongodb', 'plemi_boomgo.security.user.provider'));
        }
    }

    public function getContainerExtension()
    {
        return new PlemiBoomgoExtension();
    }
}
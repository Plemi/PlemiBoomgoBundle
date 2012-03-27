<?php

/*
 * This file is part of the PlemiBoomgoBundle.
 *
 * (c) Plemi <dev@plemi.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!($loader = @include __DIR__.'/../vendor/.composer/autoload.php')) {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'wget http://getcomposer.org/composer.phar'.PHP_EOL.
        'php composer.phar install'.PHP_EOL);
}

spl_autoload_register(function($className) {

    if (0 === strpos($className, 'Plemi\\Bundle\\BoomgoBundle\\')) {

        $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $className), 3)).'.php';

        if (stream_resolve_include_path($path)) {
            require_once $path;
            return true;
        }

        return false;
    }
});
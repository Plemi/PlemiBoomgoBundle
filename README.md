PlemiBoomgoBundle
=================

This bundle provides Symfony2 integration for [Boomgo](https://github.com/Retentio/Boomgo) : it's a lightweight and 
simple datamapper for **PHP** and **MongoDB**.

[![Build Status](https://secure.travis-ci.org/Plemi/PlemiBoomgoBundle.png?branch=master)](http://travis-ci.org/Plemi/PlemiBoomgoBundle)

Gentle introduction
-------------------

What you could find in this bundle :

* a _manager_ to ease the use of Repository class defined as a public service (DIC FTW)
* _console commands_ that will generate Mapper and Repository from your Document annoted classes

Symfony2 developers, reviews and pull requests are welcomed !

How to install ?
----------------

Prefered way is using [Composer](http://getcomposer.org/) as it also downloads dependencies and have a built-in autoloader.
At your project root level, create/update a composer.json file with :

```json
{
  "require": {
    "plemi/boomgo-bundle": "dev-master"
  }
}
```

Otherwise, you can use **Git** directly with cloning in your vendor directory both Boomgo and PlemiBoomgoBundle, but as you've done that before and as there's plenty of a examples, we won't describe it here.

Here are the 2 namespaces that you have to register in your ```autoloader``` :

```php
<?php
// app/autoload.php

'Boomgo' => 'path/to/vendor/Retentio/Boomgo/src',
'Plemi' => 'path/to/vendor/bundles',
```

Last but not least, register it in your ```AppKernel``` :

```php
<?php
// app/AppKernel.php

$bundles = array(
      ...
      new Plemi\Bundle\BoomgoBundle\PlemiBoomgoBundle(),
      ...
);
```

Show me how to use it
---------------------

This bundle works with just one requirement: you have to define at least one connection (but can register as many as you need).

A **Connection** represents a database name, a server and various options, the same as [PHP Mongo](http://fr.php.net/manual/fr/mongo.construct.php).

```yaml
plemi_boomgo:
    connections:
        myLocalConnection:
            database: myMongoDatabase
```

This bundle works with a **default_connection** name by default **default**.
Changes in the previous snippet are :

```yaml
plemi_boomgo:
    default_connection: myLocalConnection
    connections:
        myLocalConnection:
            database: myMongoDatabase
```

Need more customization on your connection ? Here's what we can call a full sample :

```yaml
plemi_boomgo:
    default_connection: myLocalConnection
    connections:
        myLocalConnection:
            database: myMongoDatabase
        myRemoteConnection:
            server: my.remotedomain.com
            database: myMongoDatabase
            options:
                connect: true
                replicatSet: myReplicaSet
```

You have to defined mapping for your document following [Boomgo explanations](https://github.com/Retentio/Boomgo). Oh by the way, an official documentation website is coming.

Then we need to generate Mapper and Repository classes :

```bash
php app/console boomgo:generate:mappers MyBundleName
php app/console boomgo:generate:repositories MyBundleName
```

Now that we have defined the configuration, generated mappers and repositories, what's the trick ? 
This bundle gives the hard work to the repository class : but it's up to you !

You can call the manager service, asking for the repository class of a valid document __on demand__ or store your implementation directly within the generated repository class. Beware of future generation process, as it rewrites the whole file.

Well, let's imagine we want to query a user collection and get the five latest :

```php
<?php
// My\Bundle\Repository\UserRepository.php

public function findOneByNameAndAge($name, $age)
{
    // Declare your query
    $query = array('name' => $name, 'age' => $age)

    // Process it
    $results = $this->getMongoCollection()->findOne($query);

    // MongoCursor to Document object
    $document = $this->getMapper()->unserialize($results)

    return $document;
}
```

Then we are able to call :

```php
<?php
// My\Bundle\Controller\UserController.php

$repository = $this->container->get('plemi_boomgo.manager')->getRepository('My\Bundle\Document\User');
$user = $repository->findOneByNameAndAge('foo', 23);
```

Wait, what about unit tests ?
-----------------------------

In order to run unit tests, you have to install [atoum](https://atoum.org) via [Composer](http://getcomposer.org/) and then execute it that way :

```bash
php composer.phar install --install-suggests
php vendor/bin/atoum -d Tests
```

Is it over yet ?
----------------

As a roadmap, planned features are :

* a **true** query logger
* a dedicated tab within Symfony2 WebDebugToolbar
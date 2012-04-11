<?php

namespace Plemi\Bundle\BoomgoBundle\Security\UserProvider;

use Plemi\Bundle\BoomgoBundle\Manager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Wrapper around a Boomgo manager.
 *
 * Provides easy to use provisioning for Boomgo document users.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class DocumentUserProvider implements UserProviderInterface
{
    private $class;
    private $repository;
    private $property;

    public function __construct(Manager $manager, $class, $property = null)
    {
        $this->class = $class;
        $this->repository = $manager->getRepository($class);
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        if (null !== $this->property) {
            $user = $this->repository->findOneBy(array($this->property => $username));
        } else {
            if (!$this->repository instanceof UserProviderInterface) {
                throw new \InvalidArgumentException(sprintf('The Doctrine repository "%s" must implement UserProviderInterface.', get_class($this->repository)));
            }

            $user = $this->repository->loadUserByUsername($username);
        }

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof $this->class) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        if ($this->repository instanceof UserProviderInterface) {
            $refreshedUser = $this->repository->refreshUser($user);
        } else {
            // The user must be reloaded via the primary key as all other data
            // might have changed without proper persistence in the database.
            // That's the case when the user has been changed by a form with
            // validation errors.
            if (!$id = $user->getId()) {
                throw new \InvalidArgumentException("You cannot refresh a user ".
                    "from the DocumentUserProvider that does not contain an identifier. ".
                    "The user object has to be serialized with its own identifier "
                );
            }

            if (null === $refreshedUser = $this->repository->find($id)) {
                throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
            }
        }

        return $refreshedUser;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === $this->class || is_subclass_of($class, $this->class);
    }
}

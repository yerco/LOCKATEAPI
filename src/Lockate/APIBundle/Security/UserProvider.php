<?php
/**
 * Created by IntelliJ IDEA.
 * User: yjorquera
 * Date: 28/02/2018
 * Time: 17:09
 */

namespace Lockate\APIBundle\Security;

use Lockate\APIBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        // make a call to your webservice here
        $userData = ["something"];
        // pretend it returns an array on success, false if there is no user

        if ($userData) {

            $password = 'lifewithoutsense';
            $salt = 'salt:P';
            $apiKey = 'ahorrendoushashhere';
            $roles = array('ROLE_USER');

            $user = new User($username, $password, $salt, $apiKey, $roles);
            return $user;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
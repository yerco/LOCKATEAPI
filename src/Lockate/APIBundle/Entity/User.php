<?php
// src/Lockate/APIBundle/Entity/User.php

namespace Lockate\APIBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $user_gateway_sensors;

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
        //return $this->roles;
        return array('ROLE_USER');
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getUserGatewaySensors()
    {
        return $this->user_gateway_sensors;
    }

    /**
     * @param mixed $user_gateway_sensors
     */
    public function setUserGatewaySensors($user_gateway_sensors)
    {
        $this->user_gateway_sensors = $user_gateway_sensors;
    }
}
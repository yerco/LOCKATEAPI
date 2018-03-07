<?php

namespace Lockate\APIBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lockate_gateway")
 */
class Gateway
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Sensor", mappedBy="gateway")
     */
    private $sensors;

    /**
     * @ORM\Column(type="integer")
     */
    private $gateway_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    public function __construct() {
        $this->sensors = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return Gateway
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSensors()
    {
        return $this->sensors;
    }

    /**
     * @param mixed $sensors
     *
     * @return Gateway
     */
    public function setSensors($sensors)
    {
        $this->sensors = $sensors;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    /**
     * @param mixed $gateway_id
     *
     * @return Gateway
     */
    public function setGatewayId($gateway_id)
    {
        $this->gateway_id = $gateway_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     *
     * @return Gateway
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

}
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
     * @ORM\OneToMany(targetEntity="Node", mappedBy="gateway")
     */
    private $nodes;

    /**
     * @ORM\Column(type="integer")
     */
    private $gateway_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $gateway_description;

    public function __construct() {
        $this->nodes = new ArrayCollection();
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
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param mixed $nodes
     *
     * @return Gateway
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;

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


    /**
     * @return mixed
     */
    public function getGatewayDescription()
    {
        return $this->gateway_description;
    }

    /**
     * @param mixed $gateway_description
     */
    public function setGatewayDescription($gateway_description)
    {
        $this->gateway_description = $gateway_description;
    }


}
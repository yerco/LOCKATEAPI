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
    private $gateway_id_real;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $gateway_summary;

    /**
     * @ORM\Column(type="datetime")
     */
    private $gateway_timestamp;

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
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @return mixed
     */
    public function getGatewayId()
    {
        return $this->gateway_id_real;
    }

    /**
     * @param mixed $gateway_id_real
     */
    public function setGatewayId($gateway_id_real)
    {
        $this->gateway_id_real = $gateway_id_real;
    }

    /**
     * @return mixed
     */
    public function getGatewaySummary()
    {
        return $this->gateway_summary;
    }

    /**
     * @param mixed $gateway_summary
     */
    public function setGatewaySummary($gateway_summary)
    {
        $this->gateway_summary = $gateway_summary;
    }

    /**
     * @return mixed
     */
    public function getGatewayTimestamp()
    {
        return $this->gateway_timestamp;
    }

    /**
     * @param mixed $gateway_timestamp
     */
    public function setGatewayTimestamp($gateway_timestamp)
    {
        $this->gateway_timestamp = $gateway_timestamp;
    }
}
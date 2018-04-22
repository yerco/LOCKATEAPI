<?php

namespace Lockate\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

// IMPORTANT the gateway_id from Gateway represents just the link with the
// lockate_gateway table
// The real `gateway_id` can be found at lockate_gateway table itself.
/**
 * @ORM\Entity
 * @ORM\Table(name="lockate_nodes")
 */
class Node
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Gateway", inversedBy="nodes")
     * @ORM\JoinColumn(name="gateway_id", referencedColumnName="id")
     */
    private $gateway;

    /**
     * @ORM\OneToMany(targetEntity="Sensor", mappedBy="node")
     */
    private $sensors;

    /**
     * @ORM\Column(type="integer")
     */
    private $node_id_real;

    /**
     * Note: if using doctrine >= 2.6 use json instead of json_array
     *      it will be due to Deprecated
     *
     * @ORM\Column(type="json_array")
     */
    private $node_summary;

    /**
     * @ORM\Column(type="datetime")
     */
    private $node_timestamp;

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
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
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
     */
    public function setSensors($sensors)
    {
        $this->sensors = $sensors;
    }

    /**
     * @return mixed
     */
    public function getNodeId()
    {
        return $this->node_id_real;
    }

    /**
     * @param mixed $node_id_real
     */
    public function setNodeId($node_id_real)
    {
        $this->node_id_real = $node_id_real;
    }

    /**
     * @return mixed
     */
    public function getNodeSummary()
    {
        return $this->node_summary;
    }

    /**
     * @param mixed $node_summary
     */
    public function setNodeSummary($node_summary)
    {
        $this->node_summary = $node_summary;
    }

    /**
     * @return mixed
     */
    public function getNodeTimestamp()
    {
        return $this->node_timestamp;
    }

    /**
     * @param mixed $node_timestamp
     */
    public function setNodeTimestamp($node_timestamp)
    {
        $this->node_timestamp = $node_timestamp;
    }
}
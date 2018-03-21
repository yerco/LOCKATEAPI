<?php

namespace Lockate\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// IMPORTANT the gateway_id here represents just the link with the
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
     * @ORM\Column(type="integer")
     */
    private $node_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $node_timestamp;

    /**
     * Note: if using doctrine >= 2.6 use json instead of json_array
     *      it will be due to Deprecated
     *
     * @ORM\Column(type="json_array")
     */
    private $analog_input;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $analog_output;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $digital_output;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $digital_input;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $txt;

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
     * @return Node
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     *
     * @return Node
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNodeId()
    {
        return $this->node_id;
    }

    /**
     * @param mixed $node_id
     *
     * @return Node
     */
    public function setNodeId($node_id)
    {
        $this->node_id = $node_id;

        return $this;
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
     *
     * @return Node
     */
    public function setNodeTimestamp($node_timestamp)
    {
        $this->node_timestamp = $node_timestamp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnalogInput()
    {
        return $this->analog_input;
    }

    /**
     * @param mixed $analog_input
     *
     * @return Node
     */
    public function setAnalogInput($analog_input)
    {
        $this->analog_input = $analog_input;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnalogOutput()
    {
        return $this->analog_output;
    }

    /**
     * @param mixed $analog_output
     *
     * @return Node
     */
    public function setAnalogOutput($analog_output)
    {
        $this->analog_output = $analog_output;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigitalOutput()
    {
        return $this->digital_output;
    }

    /**
     * @param mixed $digital_output
     *
     * @return Node
     */
    public function setDigitalOutput($digital_output)
    {
        $this->digital_output = $digital_output;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigitalInput()
    {
        return $this->digital_input;
    }

    /**
     * @param mixed $digital_input
     *
     * @return Node
     */
    public function setDigitalInput($digital_input)
    {
        $this->digital_input = $digital_input;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxt()
    {
        return $this->txt;
    }

    /**
     * @param mixed $txt
     */
    public function setTxt($txt)
    {
        $this->txt = $txt;
    }


}
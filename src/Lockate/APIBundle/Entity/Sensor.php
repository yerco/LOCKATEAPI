<?php
/** yjorquera */

namespace Lockate\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// IMPORTANT the node_id from Node represents just the link with the
// lockate_node table
// The real `node_id` can be found at lockate_node table itself.
/**
 * @ORM\Entity
 * @ORM\Table(name="lockate_sensor")
 */
class Sensor
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="sensors")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    private $node;

    /**
     * @ORM\Column(type="integer")
     */
    private $sensor_id;

    /**
     * Note: if using doctrine >= 2.6 use json instead of json_array
     *      it will be due to Deprecated
     *
     * @ORM\Column(type="json_array")
     */
    private $sensor_description;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $input;

    /**
     *
     * @ORM\Column(type="json_array")
     */
    private $output;

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
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return mixed
     */
    public function getSensorId()
    {
        return $this->sensor_id;
    }

    /**
     * @param mixed $sensor_id
     */
    public function setSensorId($sensor_id)
    {
        $this->sensor_id = $sensor_id;
    }

    /**
     * @return mixed
     */
    public function getSensorDescription()
    {
        return $this->sensor_description;
    }

    /**
     * @param mixed $sensor_description
     */
    public function setSensorDescription($sensor_description)
    {
        $this->sensor_description = $sensor_description;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param mixed $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }
}
<?php

namespace Lockate\APIBundle\Service;

use Doctrine\ORM\EntityManager;
use Lockate\APIBundle\Entity\Gateway;
use Lockate\APIBundle\Entity\Sensor;

class RetrieveSensedData
{
    private $entity_manager;

    public function __construct(EntityManager $em) {
        $this->entity_manager = $em;
    }

    /**
     * @param $gateway_id
     *
     * @return array - key id (int) -> timestamp (DateTime)
     */
    public function retrieveGatewayRecords($gateway_id) {

        $gateway_info = [];
        $gateway_records = $this->entity_manager
            ->getRepository(Gateway::class)
            ->findBy(array('gateway_id' => $gateway_id));

        for ($i = 0 ; $i < count($gateway_records); $i++){
            $gateway_info[$gateway_records[$i]->getId()] =
                $gateway_records[$i]->getTimestamp();
        }

        if (!$gateway_records) {
            return array();
        }
        else {
            return $gateway_info;
        }
    }

    /**
     * @param $gateway_id - int
     *
     * @return array of arrays
     *               [0]        id int (at lockate_sensors table)
     *               [1]        node_id int
     *               [3]        DateTime object
     *               [3] - [7]  array variable length
     *
     */
    public function retrieveGatewaySensors($gateway_id) {
        $sensor_records = [];
        $gateway_records = self::retrieveGatewayRecords($gateway_id);

        if ($gateway_records) {
            $sensorsEntity = $this->entity_manager
                ->getRepository(Sensor::class);
            foreach ($gateway_records as $gateway_id => $timestamp) {
                $result = $sensorsEntity->findBy(array('gateway' =>
                    $gateway_id));
                array_push($sensor_records, array(
                        "record_id"                => $result[0]->getId(),
                        "node_id"           => $result[0]->getNodeId(),
                        "node_timestamp"    => $result[0]->getNodeTimestamp(),
                        "analog_input"      => $result[0]->getAnalogInput(),
                        "analog_output"     => $result[0]->getAnalogOutput(),
                        "digital_input"     => $result[0]->getDigitalInput(),
                        "digital_output"    => $result[0]->getDigitalOutput(),
                        "txt"               => $result[0]->getTxt()
                    )
                );
            }
            return $sensor_records;
        }
        else {
            return array();
        }
    }


    /**
     * @param $node_id
     *
     * @return array of arrays
     *               [0]        id int (at lockate_sensors table)
     *               [1]        node_id int
     *               [3]        DateTime object
     *               [3] - [7]  array variable length
     */
    public function retrieveSensor($node_id) {
        $sensor_records = array();
        $node_records = $this->entity_manager
            ->getRepository(Sensor::class)
            ->findBy(array('node_id' => $node_id));;
        if ($node_records) {
            for ($i = 0; $i < count($node_records); $i++) {
                $sensor_records[$i] = array(
                    "record_id"         => $node_records[$i]->getId(),
                    "gateway_id"        => $node_records[$i]->getGateway()
                        ->getGatewayId(),
                    "node_id"           => $node_records[$i]->getNodeId(),
                    "node_timestamp"    =>
                        $node_records[$i]->getNodeTimestamp(),
                    "analog_input"      => $node_records[$i]->getAnalogInput(),
                    "analog_output"     => $node_records[$i]->getAnalogOutput(),
                    "digital_input"     => $node_records[$i]->getDigitalInput(),
                    "digital_output"    => $node_records[$i]->getDigitalOutput(),
                    "txt"               => $node_records[$i]->getTxt()
                );
            }
            return $sensor_records;
        }
        else {
            return array();
        }
    }

}
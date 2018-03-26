<?php

namespace Lockate\APIBundle\Service;

use Doctrine\ORM\EntityManager;
use Lockate\APIBundle\Entity\Gateway;
use Lockate\APIBundle\Entity\Node;
use Lockate\APIBundle\Entity\Sensor;

class RetrieveSensedData
{
    private $entity_manager;

    public function __construct(EntityManager $em) {
        $this->entity_manager = $em;
    }

    /**
     * @param $gateway_id
     * @param $limit
     *
     * @return array - key id (int) -> timestamp (DateTime)
     */
    public function retrieveGatewayRecords($gateway_id, $limit) {

        $gateway_info = [];
        $gateway_records = $this->entity_manager
            ->getRepository(Gateway::class)
            ->findBy(
                array('gateway_id' => $gateway_id),
                array('id'      => 'DESC'),
                $limit
            );

        for ($i = 0 ; $i < count($gateway_records); $i++){
            $gateway_info[$i] = array(
                $gateway_records[$i]->getGatewaySummary(),
                $gateway_records[$i]->getGatewayTimestamp(),
                "db_record_id"  => $gateway_records[$i]->getId()
            );
        }

        if (!$gateway_records) {
            return array();
        }
        else {
            return $gateway_info;
        }
    }

    /**
     * Note: this function uses the same class function `retrieveGatewayRecords`
     *
     * @param $gateway_id - int
     *
     * @return array of arrays
     *               [0]        id int (at lockate_sensors table)
     *               [1]        node_id int
     *               [3]        DateTime object
     *               [3] - [7]  array variable length
     *
     */
    public function retrieveGatewayNodes($gateway_id, $node_id, $limit) {
        $node_records = [];
        $gateway_records = self::retrieveGatewayRecords($gateway_id, $limit);

        for ($i = 0; $i < count($gateway_records); $i++) {
            if ($gateway_records[$i]["db_record_id"]) {
                $nodeEntity = $this->entity_manager
                    ->getRepository(Node::class);
                $result = $nodeEntity->findBy(array(
                    "gateway"   => $gateway_records[$i]["db_record_id"],
                    "node_id"   => $node_id
                    ),
                    array('id'      => 'DESC'),
                    $limit
                );

                array_push($node_records, array(
                        "node_id"       => $result[0]->getNodeId(),
                        "timestamp"     => $result[0]->getNodeTimestamp(),
                        "node_summary"  => $result[0]->getNodeSummary(),
                        "db_record_id"  => $result[0]->getId(),
                    )
                );
            }
        }
        return $node_records;
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
    public function retrieveNode($gateway_id, $node_id, $sensor_id, $limit =  null) {
        $sensor_records = array();
        $node_records = self::retrieveGatewayNodes($gateway_id, $node_id,
            $limit);

        for ($i = 0; $i < count($node_records); $i++) {
            if ($node_records[$i]["db_record_id"]) {
                $sensorEntity = $this->entity_manager
                    ->getRepository(Sensor::class);
                var_dump($node_records[$i]["db_record_id"]);
                $result = $sensorEntity->findBy(array(
                        "node"   => $node_records[$i]["db_record_id"],
                        "sensor_id" => $sensor_id

                    ),
                    array('id' => 'DESC'),
                    $limit
                );
                array_push($sensor_records, array(
                        "sensor_id"             => $result[0]->getSensorId(),
                        "sensor_description"    => $result[0]->getSensorDescription(),
                        "sensor_input"          => $result[0]->getInput(),
                        "sensor_output"         => $result[0]->getOutput(),
                        "db_record_id"  => $result[0]->getId(),
                    )
                );
            }
        }
        return $sensor_records;
    }

}
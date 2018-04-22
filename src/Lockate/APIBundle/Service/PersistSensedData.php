<?php
namespace Lockate\APIBundle\Service;

use Lockate\APIBundle\Entity\Gateway;
use Lockate\APIBundle\Entity\Node;
use Doctrine\ORM\EntityManager;
use Lockate\APIBundle\Entity\Sensor;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\Validator\Constraints\DateTime;

class PersistSensedData
{
    private $entity_manager;

    public function __construct(EntityManager $em) {
        $this->entity_manager = $em;
    }

    /**
     * @param $data - string
     *
     * @return array
     */
    public function persistSensedData($data)
    {

        $date_gateway = new \DateTime();

        $complete_record = json_decode($data);
        $record = $complete_record->gateway_record[0];

        // node_record could contain nested records
        $node_records = $record->node_record;

        if (isset($record->gateway_id)) {
            $gateway_id = $record->gateway_id;
            if (isset($record->gateway_summary)) {
                $gateway_summary = $record->gateway_summary;
            }
            else {
                $gateway_summary = json_decode('{}');
            }
            if (isset($record->timestamp)) {
                $date_gateway->setTimestamp((int)$record->timestamp);
            }
            else {
                $date_gateway->setTimestamp(0);
            }
            try {
                $gateway = new Gateway();
                $gateway->setGatewayId($gateway_id);
                $gateway->setGatewaySummary($gateway_summary);
                $gateway->setGatewayTimestamp($date_gateway);
                $this->entity_manager->persist($gateway);
            }
            catch (Exception $e) {
                return array(
                    "message"       => "Error storing information",
                    "please send this msg to the developer" => $e->getMessage()
                );
            }

            foreach ($node_records as $node_record) {
                $date_node = new \DateTime();
                if (isset($node_record->node_id)) {
                    if (isset($node_record->node_summary)) {
                        $node_summary = $node_record->node_summary;
                    }
                    else {
                        $node_summary = json_decode('{}');
                    }
                    if (isset($node_record->timestamp)) {
                        $node_timestamp = $date_node->setTimestamp((int)$node_record->timestamp);
                    }
                    else {
                        $node_timestamp = $date_node->setTimestamp(0);
                    }
                    try {
                        $node = new Node();
                        $node->setGateway($gateway->getId());
                        $node->setNodeId($node_record->node_id);
                        $node->setNodeSummary($node_summary);
                        $node->setNodeTimestamp($node_timestamp);
                        //relates node to gateway
                        $node->setGateway($gateway);
                        $this->entity_manager->persist($node);

                        foreach ($node_record->sensor_record as $sensor_record) {

                            if (isset($sensor_record->sensor_id)) {
                                if (isset($sensor_record->sensor_description)) {
                                    $sensor_description = $sensor_record->sensor_description;
                                }
                                else {
                                    $sensor_description = json_decode('{}');
                                }
                                if (isset($sensor_record->input)) {
                                    $sensor_input =  $sensor_record->input;
                                }
                                else {
                                    $sensor_input = json_decode('{}');
                                }
                                if (isset($sensor_record->output)) {
                                    $sensor_output = $sensor_record->output;
                                }
                                else {
                                    $sensor_output = json_decode('{}');
                                }
                                try {
                                    $sensor = new Sensor();
                                    $sensor->setSensorId($sensor_record->sensor_id);
                                    $sensor->setSensorDescription($sensor_description);
                                    $sensor->setInput($sensor_input);
                                    $sensor->setOutput($sensor_output);
                                    //relates sensor to node
                                    $sensor->setNode($node);
                                    $this->entity_manager->persist($sensor);
                                }
                                catch (Exception $e) {
                                    return array(
                                        "message"       => "Error storing information",
                                        "please send this msg to the developer" => $e->getMessage()
                                    );
                                }
                            }
                        }

                    }
                    catch (Exception $e) {
                        return array(
                            "message"       => "Error storing information",
                            "please send this msg to the developer" => $e->getMessage()
                        );
                    }
                }
            }
            try {
                $this->entity_manager->flush();
            }
            catch(DriverException $e) {
                return array(
                    "message"   => $e->getMessage()
                );
            }

            $time = time();
            $check = $time + date("Z", $time);

            return array(
                "message" => "information stored",
                "storage_time" => strftime("%B %d, %Y @ %H:%M:%S UTC", $check)
            );
        } else {
            return array(
                "message"   => "gateway_id not set"
            );
        }
    }
}
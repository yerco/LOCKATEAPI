<?php
namespace Lockate\APIBundle\Service;

use Lockate\APIBundle\Entity\Gateway;
use Lockate\APIBundle\Entity\Node;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Exception\DriverException;

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
    public function persistSensedData($data) {

        $date = new \DateTime();

        $complete_record = json_decode($data);
        $record = $complete_record->gateway_record[0];

        // node_record could contain nested records
        $node_record = $record->node_record;

        $gateway_id = $record->gateway_id;
        $timestamp = $date->setTimestamp((int)$record->timestamp);
        $gateway_description = $record->gateway_description;

        try {
            $gateway = new Gateway();
            $gateway->setGatewayId($gateway_id);
            $gateway->setTimestamp($timestamp);
            $gateway->setGatewayDescription($gateway_description);
            $this->entity_manager->persist($gateway);
        }
        catch (Exception $e) {
            //var_dump($e);
            return array(
                "message"       => "Error storing information",
                "please send this msg to the developer" => $e->getMessage()
            );
        }
        foreach ($node_record as $sensor_record) {

            $sensor = new Node();

            $sensor->setNodeId($sensor_record->node_id);
            $date = new \DateTime();
            $sensor->setNodeTimestamp($date->setTimestamp((int)$sensor_record->timestamp));

            if (isset($sensor_record->ai)) {
                $sensor->setAnalogInput($sensor_record->ai);
            }
            else {
                $sensor->setAnalogInput(json_decode('{}'));
            }
            if (isset($sensor_record->ao)) {
                $sensor->setAnalogOutput($sensor_record->ao);
            }
            else {
                $sensor->setAnalogOutput(json_decode('{}'));
            }
            if (isset($sensor_record->di)) {
                $sensor->setDigitalInput($sensor_record->di);
            }
            else {
                $sensor->setDigitalInput(json_decode('{}'));
            }
            if (isset($sensor_record->do)) {
                $sensor->setDigitalOutput($sensor_record->do);
            }
            else {
                $sensor->setDigitalOutput(json_decode('{}'));
            }
            if (isset($sensor_record->txt)) {
                $sensor->setTxt($sensor_record->txt);
            }
            else {
                $sensor->setTxt(json_decode('{}'));
            }
            //relates sensor to gateway
            $sensor->setGateway($gateway);

            $this->entity_manager->persist($sensor);
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
        $check = $time+date("Z",$time);

        return array(
            "message"       => "information stored",
            "storage_time"  => strftime("%B %d, %Y @ %H:%M:%S UTC", $check)
        );
    }
}
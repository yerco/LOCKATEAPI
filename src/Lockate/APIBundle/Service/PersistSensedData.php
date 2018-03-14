<?php
namespace Lockate\APIBundle\Service;

use Lockate\APIBundle\Entity\Gateway;
use Lockate\APIBundle\Entity\Sensor;
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

        try {
            $gateway = new Gateway();
            $gateway->setGatewayId($gateway_id);
            $gateway->setTimestamp($timestamp);
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

            $sensor = new Sensor();

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
                "message"   => "check json package (hint timestamp length)"
            );
        }
        $time = time();
        $check = $time+date("Z",$time);

        return array(
            "message"       => "information stored",
            "storage_time"  => strftime("%B %d, %Y @ %H:%M:%S UTC", $check)
        );
    }

    // TODO
    public function getGatewayInfo($gateway_id) {
//        $gateway = $this->entity_manager
//            ->getRepository(Sensor::class)
//            ->find($gateway_id);
        $query = $this->entity_manager
            ->createQuery('SELECT g FROM LockateAPIBundle:Gateway g
                          WHERE g.gateway_id = :gateway_id')
            ->setParameter('gateway_id', $gateway_id);

        try {
            $result = $query->getResult();
            echo "Son " . count($result) . "\n";
//            var_dump($result[0]->getSensors()[0]->getNodeId());
            var_dump($result[0]->getSensors()[0]->getNodeId());

            return new JsonResponse($result);
        }
        catch (\Doctrine\ORM\NoResultException $exception) {
            echo "\n La excepcion";
            var_dump($exception);
            return new JsonResponse(array(
                "message"   => "no results found"
                )
            );
        }
    }
}
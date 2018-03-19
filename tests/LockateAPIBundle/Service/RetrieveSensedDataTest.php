<?php

namespace Lockate\APIBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Lockate\APIBundle\Service\RetrieveSensedData;

class RetrieveSensedDataTest extends KernelTestCase
{
    // Note: DB needs to be populated for gateway_id = 0
    public function testRetrieveGatewayThroughGatewayId() {
        $gateway_id = 0;
        $limit = 2;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveGatewayRecords($gateway_id, $limit);
        var_dump($response);
        $this->assertNotEmpty($response);
    }

    public function testRetrieveGatewaySensors() {
        $gateway_id = 0;
        $limit = 1;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveGatewaySensors($gateway_id, $limit);
        //var_dump($response);
        $this->assertNotEmpty($response);
    }

    public function testRetrieveSensor() {
        $sensor_id = 27; // node_id
        $limit = 1;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveSensor($sensor_id, $limit);
        $this->assertNotEmpty($response);
    }
}
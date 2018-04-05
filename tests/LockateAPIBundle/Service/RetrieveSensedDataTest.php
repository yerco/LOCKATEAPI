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
        // var_dump($response);
        $this->assertNotEmpty($response);
    }

    public function testRetrieveGatewayNodes() {
        $gateway_id = 12;
        $node_id = 27;
        $limit = null;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveGatewayNodes($gateway_id, $node_id, $limit);
        var_dump($response);
        $this->assertNotEmpty($response);
    }

    public function testRetrieveGatewayNodeSensors() {
        $gateway_id = 12;
        $node_id = 27;
        $sensor_id = 5;
        $limit = 1;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveNode($gateway_id, $node_id, $sensor_id, $limit);

        $this->assertNotEmpty($response);
    }
}
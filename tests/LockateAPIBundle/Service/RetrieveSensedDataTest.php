<?php

namespace Lockate\APIBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Lockate\APIBundle\Service\RetrieveSensedData;

class RetrieveSensedDataTest extends KernelTestCase
{
    // Note: DB needs to be populated for gateway_id = 0
    public function testRetrieveGatewayThroughGatewayId() {
        $gateway_id = 1;
        $limit = 2;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveGatewayRecords($gateway_id, $limit);
        // var_dump($response);
        $this->assertNotEmpty($response);
    }

    public function testRetrieveGatewayNodes() {
        $gateway_id = 1;
        $node_id = 1;
        $limit = null;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveGatewayNodes($gateway_id, $node_id, $limit);
        //var_dump($response);
        $this->assertNotEmpty($response);
    }

    public function testRetrieveGatewayNodeSensors() {
        $gateway_id = 1;
        $node_id = 1;
        $sensor_id = 1;
        $limit = 10;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveNode($gateway_id, $node_id, $sensor_id, $limit);

        $this->assertNotEmpty($response);
    }

    public function testRetrieveRecordsGatewayTime() {
        $gateway_id = 1;
        $node_id = 1;
        $sensor_id = 1;
        $start_date = '2018-04-05';
        $start_hour = '15';
        $start_minute = '34';
        $start_second = '40';
        $end_date =  '2018-04-05';
        $end_hour = '15';
        $end_minute = '35';
        $end_second = '00';
        $limit = 1;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveRecordsGatewayTime($gateway_id,
            $node_id, $sensor_id,
            $start_date, $start_hour, $start_minute, $start_second,
            $end_date, $end_hour, $end_minute, $end_second,
            $limit
        );z
        //var_dump($response);
        $this->assertInternalType('array', $response);
    }

    public function testRetrieveLastEvents(){
        $limit = 1;
        $gateway_id = 1;
        $kernel = self::bootKernel();
        $entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $retriever = new RetrieveSensedData($entity_manager);
        $response = $retriever->retrieveLastGatewayEvents($gateway_id, $limit);
        $this->assertEquals($limit, count($response));
    }
}
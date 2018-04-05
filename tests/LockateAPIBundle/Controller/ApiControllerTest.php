<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ApiControllerTest extends WebTestCase
{
    protected $json_test_package_A;

    protected function setUp() {

        $json_test_package = '
            {
                "gateway_record": [
                    {
                        "gateway_id": 1,
                        "gateway_summary": {
                            "device": "RPI",
                            "name": "naifo_gateway",
                            "location": "penalolen"
                        },
                        "node_record": [
                            {
                                "node_summary": {
                                    "phones_around": 2,
                                    "name": "naifo_node",
                                    "phones_detected": 2
                                },
                                "node_id": 1,
                                "timestamp": "1522437699",
                                "sensor_record": [
                                    {
                                        "input": [
                                            {
                                                "rssi": -90.4125,
                                                "company": "ARRIS Group, Inc.",
                                                "mac": "54:65:de:60:d0:b0"
                                            },
                                            {
                                                "rssi": -61.901408450704224,
                                                "company": "HUAWEI TECHNOLOGIES CO.,LTD",
                                                "mac": "7c:7d:3d:ab:c1:92"
                                            }
                                        ],
                                        "sensor_id": 1,
                                        "sensor_description": {
                                            "name": "Atheros"
                                        }
                                    }
                                ]
                            }
                        ],
                        "timestamp": "1522437699"
                    }
                ]
            }
            ';

        // just cleaning this stuff above to make a proper json
        $temp = str_replace(array("\n", "\r"), '', $json_test_package);
        $temp = preg_replace('/\s+/', '', $temp);
        $this->json_test_package_A = $temp;

    }

    public function testSensedDataEndpointUsingGuzzle() {

        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'uno']);

        $client = new Client([
            'base_uri'  => 'http://localhost:81',
            'timeout'   => 2.0,
            'headers'   => [
                'Authorization' => 'Bearer '.$token
            ],
        ]);

        $response = $client->request(
            'POST',
            '/api/v1/senseddata',
            [
                'json' => json_decode($this->json_test_package_A, true)
            ]
        );

        var_dump($response->getBody()->getContents());

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function getAuthorizedHeaders($username, $headers = array()) {
        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $username]);

        $headers['Authorization'] = 'Bearer '.$token;

        return $headers;
    }

    public function testGetGatewayDataUsingGatewayId() {

        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'uno']);
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'Authorization' => 'Bearer '. $token
            ],
        ]);
        $gateway_id = 12;
        $limit = 1;
        $endpoint = '/api/v1/gateway/' . $gateway_id;
        if ($limit > 0) {
            $endpoint = '/api/v1/gateway/' . $gateway_id . '/' . $limit;
        }
        $response = $client->request(
            'GET',
            $endpoint
        );

        //var_dump($response->getBody()->getContents());
        $this->assertNotEmpty($response->getBody()->getContents());
    }

    public function testGetGatewayNodes() {

        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'uno']);
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'Authorization' => 'Bearer '. $token
            ],
        ]);
        $gateway_id = 12;
        $node_id = 27;
        $limit = 1;
        $endpoint = '/api/v1/gatewaynodes/' . $gateway_id . '/' . $node_id . '/' . $limit;
        if ($limit > 0) {
            $endpoint = '/api/v1/gateway/' . $gateway_id . '/' . $limit;
        }
        $response = $client->request(
            'GET',
            $endpoint
        );
        //var_dump($response->getBody()->getContents());
        $this->assertNotEmpty($response->getBody()->getContents());

    }

    public function testGetGatewayNodeSensors() {

        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'uno']);
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'Authorization' => 'Bearer '. $token
            ],
        ]);
        $gateway_id = 12;
        $node_id = 27;
        $sensor_id = 5;
        $limit = 1;
        $endpoint = '/api/v1/nodeinfo/' . $gateway_id . '/' . $node_id . '/' .
            $sensor_id . '/' . $limit;
        $response = $client->request(
            'GET',
            $endpoint
        );
        //var_dump($response->getBody()->getContents());
        $this->assertNotEmpty($response->getBody()->getContents());
    }
}

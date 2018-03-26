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
                        "gateway_id": 12,
                        "gateway_summary": {
                            "name": "nombre",
                            "location": "Arica",
                            "device": "RPI"
                        },
                        "timestamp": 1519912976,
                        "node_record": [
                            {
                                "node_id": 27,
                                "node_summary": {
                                    "name": "nombre"
                                },
                                "timestamp": "1519912976",
                                "sensor_record": [
                                    {
                                        "sensor_id": 5,
                                        "sensor_description": {
                                            "name": "nombre"
                                        },
                                        "input": [
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            },
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            },
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            }
                                        ],
                                        "output": [
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            },
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            },
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            }
                                        ]
                                    },
                                    {
                                        "sensor_id": 8,
                                        "sensor_description": {
                                            "name": "nombre"
                                        },
                                        "input": [
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            },
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            },
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            }
                                        ],
                                        "output": [
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            },
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            },
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                "node_id": 13,
                                "node_summary": {
                                    "name": "nombre"
                                },
                                "timestamp": "1519912976",
                                "sensor_record": [
                                    {
                                        "sensor_id": 100,
                                        "sensor_description": {
                                            "name": "nombre"
                                        },
                                        "input": [
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            },
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            },
                                            {
                                                "rssi": -84,
                                                "mac": "80:e6:50:xx:xx:xx",
                                                "company": "Apple, Inc."
                                            }
                                        ],
                                        "output": [
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            },
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            },
                                            {
                                                "motor1": 1,
                                                "motor2": 0,
                                                "motor3": 1
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
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
            'base_uri'  => 'http://localhost',
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

        //var_dump($response);
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

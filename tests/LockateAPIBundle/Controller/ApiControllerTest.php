<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ApiControllerTest extends WebTestCase
{
    protected $json_test_package_A;
    protected $json_test_package_B;
    protected $json_test_package_C;

    protected function setUp() {
        $json_test_package = '
            {
                "gateway_record": 
                    [
                        {
                            "gateway_id": 0,
                            "timestamp": "1519912976000", 
                            "node_record": [
                                        {
                                        "node_id": 27, 
                                        "timestamp": "1519912976000",
                                        "di": {"di_1": "on", "di_2": "off"}, 
                                        "ai": {"ai": 25.74}
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

        $json_test_package = '
            {
                "gateway_record": 
                [
                    {
                        "gateway_id": 2,
                        "timestamp": "", 
                        "node_record": [
                            {
                                "node_id": 27, 
                                "timestamp": "1519912976000",
                                "di": {"di_1": "on", "di_2": "off"},  
                                "ai": {"ai_1": 25.74}
                            },
                            {
                                "node_id": 27, 
                                "timestamp": "1519913380000",
                                "di": {"di_1": "off", "di_2": "off"},  
                                "ai": {"ai_1": 20.1}
                            },
                            {
                                "node_id": 14, 
                                "timestamp": "1519914560000",
                                "di": {"di_1": "off", "di_2": "off", "di_3": "on"},  
                                "do": {"do_1": "off"},
                                "ai": {"ai_1": 23.1, "ai_2": 13.2, "ai_3": 0.45}
                            }
                        ]    
                    }
                ]
            }
            ';
        // just cleaning this stuff above to make a proper json
        $temp = str_replace(array("\n", "\r"), '', $json_test_package);
        $temp = preg_replace('/\s+/', '', $temp);
        $this->json_test_package_B = $temp;

        $json_test_package = '
            {
                "gateway_record": 
                    [
                        {
                            "gateway_id": 0,
                            "timestamp": "1521025377", 
                            "node_record": [
                                {
                                "node_id": 27, 
                                "timestamp": "1521025387",
                                "ai": {"rssi": -86.0},
                                "txt": {"mac": "90:e7:c4:xx:xx:xx", "company": "HTC Corporation"}
                                }                      
                            ]  
                        }
                    ]
            }
        ';
        // just cleaning this stuff above to make a proper json
        $temp = str_replace(array("\n", "\r"), '', $json_test_package);
        $temp = preg_replace('/\s+/', '', $temp);
        $this->json_test_package_C = $temp;
    }

    public function testSensedDataEndpointUsingGuzzle() {

        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'uno']);

        $json_test_package = json_encode($this->json_test_package_A);
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'X-AUTH-TOKEN'  => 'schmier',
                'headers' => $this->getAuthorizedHeaders('uno'),
                'Authorization' => 'Bearer '.$token
            ],
            // momentary
            //'allow_redirects' => false
        ]);

        $response = $client->request(
            'POST',
            '/api/v1/senseddata',
            ['json' => $json_test_package],
            ['auth' => ['uno', 'uno']]
        );

//      // var_dump($response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function getAuthorizedHeaders($username, $headers = array()) {
        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $username]);

        $headers['Authorization'] = 'Bearer '.$token;

        return $headers;
    }

}

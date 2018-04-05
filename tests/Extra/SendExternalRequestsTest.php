<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class SendExternalRequestsTest extends TestCase
{
    protected $json_packet_string;
    protected function setUp()
    {
        $this->json_packet_string = '
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
    }


    /* sending request to the website counterpart */
    public function testForwardRequests() {

        $endpoint_url = "http://localhost/app_dev.php/site";
        $string_json = $this->json_packet_string;
        $username = "uno";
        $password = "uno";
        $client = new Client();
        $options= array(
            'auth' => [
                $username,
                $password
            ],
            'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],
            'body' => $string_json,
            "debug" => true
        );
        try {
            $response = $client->post($endpoint_url, $options);
            //var_dump($response->getBody()->getContents());
            $this->assertEquals(200, $response->getStatusCode());
        } catch (ClientException $e) {
            echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                echo $e->getResponse() . "\n";
            }
        }
    }
}
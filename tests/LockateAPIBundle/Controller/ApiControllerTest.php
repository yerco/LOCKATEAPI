<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ApiControllerTest extends WebTestCase
{
    protected $json_test_package_A;
    protected $json_test_package_B;

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
    }

    public function testSensedDataEndpointUsingGuzzle() {

        $token = "eyJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6InVubyIsImV4cCI6MTUyMDY3OTYwNCwiaWF0IjoxNTIwNjc2MDA0fQ.hinj99QkDxw4C_TA6B7pxg5O7MpDja0HLCVKPLndq4fcumaLmP31teaoHIE84Utf1Va81Y53ifKTMYef6U5egJ9Gz3SfguTvqyH5kl4u3xaAUhDgeYARCyBJ7BWdUdXOT5YRc_1CSq-kyCs4_czzHqwYpU_G49L_jA7du4JOE0PHH2GUUWP7qxjCoEiBvXFnaoMCmkrerIOP6bvYcqwB5vNGxeHbEAZedOo_h22mK-QfTdXwgHzBCv7_cXhe3aj3-ayR4_azn8JposJVhzoGSBuIX0pDPiZvvTFI7qdwxkq8bwtgVg-iniYIEFW95Kwg51Cl0WzuTA2rgV5abRYXzp_6x-cSb7yGk00GKtKbUFKuULJ-XqiAtL7s09Fc3bl_z1WCLeTv7uVPCIg7QEC2DkWsSqmiAkqoQ7JU-4x048i5pmVwY-Vi-vsU_m7pFX8S_c8eg4LK5F3aoUGdoCqjXIq6utuIWF8oVfN0RfxG7dl-K0xPMh9v_HZtIXZNoE_i9-mH4OORHaigbH27SsnZhtPdE3eXUqd4Y_AVuqTeRIYtt21zuABl7rpkjaaMudPahl6V0kq09JYaQSJqCzfEs8a7EzEWbEK2HzTjiScseizbT4roJPYF_B7psiVyT38Vtu8GoCERVipA4HS6qA_8txOYmU0JYUiN5ebdVBkVwRw";
        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'uno']);

        var_dump($token);
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

//        var_dump($response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function getAuthorizedHeaders($username, $headers = array()) {
        $kernel = self::bootKernel();
        $token = $kernel->getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $username]);

        $headers['Authorization'] = 'Bearer '.$token;

        return $headers;
    }

    // TODO
//    public function testSensedDataEndpointUsingInternalClient() {
//        $client = static::createClient();
//        //var_dump(json_decode($this->json_test_package));
//        $client->request(
//            'POST',
//            '/api/v1/senseddata',
//            (array)$this->json_test_package_B,
//            array(), // files
//            array(
//                'CONTENT_TYPE' => 'application/json',
//                // IMPORTANT:
//                // headers are part of `$_SERVER`
//                // in this case without the `HTTP_` prefix this does not work
//                'HTTP_X-AUTH-TOKEN'  => 'schmier'
//            )
//        );
//        var_dump($client->getRequest());
//        $response = $client->getResponse()->getStatusCode();
//        //var_dump($response);
//    }

}

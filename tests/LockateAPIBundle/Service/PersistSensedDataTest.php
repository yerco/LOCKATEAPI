<?php
namespace Lockate\APIBundle\Tests\Service;

use Lockate\APIBundle\Service\PersistSensedData;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PersistSensedDataTest extends KernelTestCase
{
    protected $json_test_package_A;
    protected $json_test_package_B;
    protected $entity_manager;

    protected function setUp()
    {
        $json_test_package
            = '
            {
                "gateway_record": 
                    [
                        {
                            "gateway_id": 0,
                            "timestamp": "1520418893", 
                            "node_record": [
                                        {
                                        "node_id": 27, 
                                        "timestamp": "1520428893",
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

        $json_test_package
            = '
            {
                "gateway_record": 
                [
                    {
                        "gateway_id": 2,
                        "timestamp": "1520258893", 
                        "node_record": [
                            {
                                "node_id": 27, 
                                "timestamp": "1520258893",
                                "di": {"di_1": "on", "di_2": "off"},  
                                "ai": {"ai_1": 25.74}
                            },
                            {
                                "node_id": 27, 
                                "timestamp": "1520300881",
                                "di": {"di_1": "off", "di_2": "off"},  
                                "ai": {"ai_1": 20.1}
                            },
                            {
                                "node_id": 14, 
                                "timestamp": "1520420881",
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

        $kernel = self::bootKernel();
        $this->entity_manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testPersistSensedDataPassingAJSON() {
        $persist = new PersistSensedData($this->entity_manager);
        $response = $persist->persistSensedData($this->json_test_package_B);
        // Assuming return `message` implies data was received successfully
        $this->assertNotEmpty($response["message"]);
    }

    //TODO
//    public function testGetSensorsWhichAreLinkedToAGatewayInfo() {
//        $gateway_id = 2;
//        $persist = new PersistSensedData($this->entity_manager);
//        $response = $persist->getGatewayInfo($gateway_id);
//        echo "\nque\n";
//        //var_dump($response->getContent());
//    }
}

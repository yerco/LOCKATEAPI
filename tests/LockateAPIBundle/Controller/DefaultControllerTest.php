<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /*
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Hello World', $client->getResponse()->getContent());
    }
    */

    public function testRootResponse() {
        $client = static::createClient();
        $client->request('GET', '/');
        $json_response = json_decode($client->getResponse()->getContent());
        $this->assertEquals($json_response->message, "alive");
    }

    public function testRootResponseUsingCurl() {
        $response = $this->CallAPI('GET', 'localhost');
        $response = json_decode($response);
        $this->assertEquals($response->message, "alive");
    }

    /**
     * A loop, what was sent is received
     */
    public function testAPIsSensorsRoute() {
        $payload = array(
            'node_id'   => '2714',
            'timestamp' => '1565',
            'var1'      => '12365625'
        );
        $data_json = json_encode($payload);
        $response = $this->CallAPI(
            'POST',
            'localhost/api/v1/sensors',
            $data_json
        );
        $this->assertEquals(json_encode($payload), $response);

    }

    private function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
}

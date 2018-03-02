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
    /*previous test without authentication Token */
    /*
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
    */
    public function testAPIsSensorsRoute() {
        $payload = array(
            'node_id'   => '2714',
            'timestamp' => '1565',
            'var1'      => '12365625'
        );
        $data_json = json_encode($payload);
        $token = 'schmier';
        $response = $this->CallAPI(
            'POST',
            'localhost/api/v1/sensors',
            $data_json,
            $token
        );
        $this->assertEquals(json_encode($payload), $response);
    }

    /* 401 unauthorized */
    public function testAuthenticationRequired() {
        $payload = array(
            "node_id"   => "colorado",
            "timestamp" => "1234567890",
            "var1"      => "peace sells"
        );
        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/api/v1/sensors',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json'
            ),
            json_encode($payload)
        );
        $response = $client->getInternalResponse();
        // 401 UNAUTHORIZED
        $this->assertEquals(401, $response->getStatus());
    }

    /* 403 Forbidden */
    public function test403Required() {
        $payload = array(
            "node_id"   => "colorado",
            "timestamp" => "1234567890",
            "var1"      => "peace sells"
        );
        $payload = array(
            'node_id'   => '2714',
            'timestamp' => '1565',
            'var1'      => '12365625'
        );
        $data_json = json_encode($payload);
        $token = 'badtoken';
        $response = $this->CallAPI(
            'POST',
            'localhost/api/v1/sensors',
            $data_json,
            $token
        );
        $this->assertEquals('{"message":"Check your credentials"}', $response);
    }

    // the one that uses `PHP_AUTH_USER`
    public function testBasicAuth() {
        $payload = array(
            "node_id"   => "colorado",
            "timestamp" => "1234567890",
            "var1"      => "peace sells"
        );
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'username',
                'PHP_AUTH_PW'   => 'pa$$word',
            )
        );
        $crawler = $client->request(
            'POST',
            '/api/v1/sensors',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'X-AUTH-TOKEN ' =>'PendingSTUFF'
            ),
            json_encode($payload)
        );
        //var_dump($client->getRequest());
    }

    /**
     * Utility function for making REST calls
     *
     * @param      $method - POST; PUT or GET
     * @param      $url
     * @param bool $data
     *
     * @return mixed
     */
    private function CallAPI($method, $url, $data = false, $token = false)
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
        if ($token !== false) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'X-AUTH-TOKEN: ' . $token
                )
            );
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
}

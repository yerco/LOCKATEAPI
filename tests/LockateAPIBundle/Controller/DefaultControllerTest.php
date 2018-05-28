<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;
// Client Exception inherits from GuzzleException so commented out
//use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class DefaultControllerTest extends WebTestCase
{
    public function testRootResponse() {
        $client = static::createClient();
        $client->request('GET', '/');
        $response = $client->getResponse()->getContent();
        $this->assertContains('Remember me', $response, "alive");
    }

    public function testRootResponseUsingCurl() {
        $response = $this->CallAPI('GET', 'localhost');
        $this->assertContains('Remember me', $response, "alive");
    }

    /* Using Guzzle */
    public function testRootResponseUsingGuzzle() {
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0
        ]);
        try {
            $response = $client->request('GET', '/');
        }
        catch(GuzzleException $e) {
            echo $e->getMessage();
        }
        $this->assertEquals(200, $response->getStatusCode());
    }

    /* 403 forbidden */
    public function testCheckCredentialsRequiredUsingGuzzle() {
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'X-AUTH-TOKEN'  => 'nottherightone'
            ]
        ]);
        $json_payload = [
            'node_id'   => 'uno',
            'timestamp' => 1234567890,
            'var1'      => 'fatboyslim'
        ];
        try {
            $client->request(
                'POST',
                '/api/v1/sensors',
                ['json' => $json_payload]
            );
        }
        catch(GuzzleException $e) {
            echo $e->getMessage();
            $this->assertEquals(403, $e->getCode());
        }
    }

    /* 302 redirected - do not have customer header */
    public function testNotCustomHeaderAttachedUsingGuzzle() {
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'allow_redirects' => false
        ]);
        $json_payload = [
            'node_id'   => 'uno',
            'timestamp' => 1234567890,
            'var1'      => 'fatboyslim'
        ];

        try {
            $response = $client->request(
                'POST',
                '/api/v1/sensors',
                ['json' => $json_payload]
            );
        }
        catch(GuzzleException $e) {
            echo $e->getMessage();
            $this->assertEquals(403, $e->getCode());
        }
        $this->assertEquals(302, $response->getStatusCode());
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
        $token = 'schmier';
        $response = $this->CallAPI(
            'POST',
            'localhost/api/v1/sensors',
            $data_json,
            $token
        );
        $this->assertEquals(json_encode($payload), $response);
    }

    public function testAPIsSensorsRouteUsingGuzzle() {
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'X-AUTH-TOKEN'  => 'schmier'
            ]
        ]);
        $json_payload = [
            'node_id'   => 'uno',
            'timestamp' => 1234567890,
            'var1'      => 'fatboyslim'
        ];
        try {
            $response = $client->request(
                'POST',
                '/api/v1/sensors',
                ['json' => $json_payload]
            );
        }
        catch(GuzzleException $e) {
            echo $e->getMessage();
            $this->assertEquals(403, $e->getCode());
        }
        $json_received = (array) json_decode($response->getBody()->getContents());
        $this->assertEquals($json_payload, $json_received);
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

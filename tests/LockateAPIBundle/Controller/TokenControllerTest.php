<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class TokenControllerTest extends WebTestCase
{
    public function testPOSTCreateToken() {

        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'X-AUTH-TOKEN'  => 'schmier'
            ],
            // momentary
            //'allow_redirects' => false
        ]);

        $response = $client->request(
            'POST',
            '/api/v1/tokens',
            ['auth' => ['weaverryan', 'I<3Pizza']]
        );
    }
}
<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class TokenControllerTest extends WebTestCase
{
    public function testPOSTCreateToken() {

        $token = "eyJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6InVubyIsImV4cCI6MTUyMDY3OTYwNCwiaWF0IjoxNTIwNjc2MDA0fQ.hinj99QkDxw4C_TA6B7pxg5O7MpDja0HLCVKPLndq4fcumaLmP31teaoHIE84Utf1Va81Y53ifKTMYef6U5egJ9Gz3SfguTvqyH5kl4u3xaAUhDgeYARCyBJ7BWdUdXOT5YRc_1CSq-kyCs4_czzHqwYpU_G49L_jA7du4JOE0PHH2GUUWP7qxjCoEiBvXFnaoMCmkrerIOP6bvYcqwB5vNGxeHbEAZedOo_h22mK-QfTdXwgHzBCv7_cXhe3aj3-ayR4_azn8JposJVhzoGSBuIX0pDPiZvvTFI7qdwxkq8bwtgVg-iniYIEFW95Kwg51Cl0WzuTA2rgV5abRYXzp_6x-cSb7yGk00GKtKbUFKuULJ-XqiAtL7s09Fc3bl_z1WCLeTv7uVPCIg7QEC2DkWsSqmiAkqoQ7JU-4x048i5pmVwY-Vi-vsU_m7pFX8S_c8eg4LK5F3aoUGdoCqjXIq6utuIWF8oVfN0RfxG7dl-K0xPMh9v_HZtIXZNoE_i9-mH4OORHaigbH27SsnZhtPdE3eXUqd4Y_AVuqTeRIYtt21zuABl7rpkjaaMudPahl6V0kq09JYaQSJqCzfEs8a7EzEWbEK2HzTjiScseizbT4roJPYF_B7psiVyT38Vtu8GoCERVipA4HS6qA_8txOYmU0JYUiN5ebdVBkVwRw";
        $client = new Client([
            'base_uri'  => 'http://localhost',
            'timeout'   => 2.0,
            'headers'   => [
                'X-AUTH-TOKEN'  => 'schmier',
                'Authorization' => 'Bearer '.$token
            ],
            // momentary
            //'allow_redirects' => false
        ]);

        $response = $client->request(
            'POST',
            //'/api/v1/tokens',
            '/api/v1/tokens',
            ['auth' => ['unol', 'uno']]
        );
        $this->assertEquals(200, $response->getStatusCode());
//        $this->asserter()->assertResponsePropertyExists(
//            $response,
//            'token'
//        );
    }
}
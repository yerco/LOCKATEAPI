<?php

namespace Lockate\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    public function sensorsAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $request_content = $request->getContent();
        $request_content = json_decode($request_content);
        $response = array(
            'node_id'   => $request_content->node_id,
            'timestamp' => $request_content->timestamp,
            'var1'      => $request_content->var1
        );
        $response = new JsonResponse($response);
        $response->headers->set('Location', $request->getUri());
        return $response;
    }

    public function sensedDataAction(Request $request) {
        $username = $request->headers->get('php-auth-user');
        $password = $request->headers->get('php-auth-pw');
        $user = $this->getDoctrine()
            ->getRepository('LockateAPIBundle:User')
            ->findOneBy(['username' => $username]);

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);

        if (!$isValid) {
            return new JsonResponse(
                array("message" => "Check credentials")
            );
        }

        $persist = $this->get('persist_senseddata');
        $persistence_message = $persist->persistSensedData($request->getContent());
        return new JsonResponse($persistence_message);
    }

}
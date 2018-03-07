<?php

namespace Lockate\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Lockate\APIBundle\Service\PersistSensedData;

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
        $this->denyAccessUnlessGranted('ROLE_USER');
        //var_dump($request->getContent());
        $persist = new PersistSensedData(
            $this->getDoctrine()->getManager()
        );
        $persistence_message = $persist->persistSensedData($request->getContent());

        return new JsonResponse($persistence_message);
    }


}
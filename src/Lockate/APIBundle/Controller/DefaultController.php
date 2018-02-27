<?php

namespace Lockate\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $response = new JsonResponse(array("message" => "alive"));
        // kept here to remember syntax which includes `@`
        // return $this->render('@LockateAPI/Default/index.html.twig');
        return $response;
    }

    public function sensorsAction(Request $request) {
        $request_content = $request->getContent();;
        $request_content = json_decode($request_content);
        $response = array(
            'node_id'   => $request_content->node_id,
            'timestamp' => $request_content->timestamp,
            'var1'      => $request_content->var1
        );
        return new JsonResponse($response);
    }
}

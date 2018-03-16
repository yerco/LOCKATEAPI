<?php

namespace Lockate\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Lockate\APIBundle\Event\SensorSideEvent;

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

        $persist = $this->get('persist_senseddata');
        $persistence_message = $persist->persistSensedData($request->getContent());
        $event_dispatcher = new EventDispatcher();
        $event = new SensorSideEvent($request->getContent());
        $senseddata_listener = $this->container->get('sensed_data_request');
        $event_dispatcher->addSubscriber($senseddata_listener);
        $event_dispatcher->dispatch(SensorSideEvent::SENSEDDATAREQUEST, $event);
        return new JsonResponse($persistence_message);
    }

}
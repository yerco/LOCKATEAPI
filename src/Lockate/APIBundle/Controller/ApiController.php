<?php

namespace Lockate\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Lockate\APIBundle\Event\NodeSideEvent;

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
        //echo "\n\n\nHola\n";
        //var_dump($request->getContent());
        $persistence_message = $persist->persistSensedData($request->getContent());
        $event_dispatcher = new EventDispatcher();
        $event = new NodeSideEvent($request->getContent());
        $senseddata_listener = $this->container->get('captured_data_request');
        $event_dispatcher->addSubscriber($senseddata_listener);
        $event_dispatcher->dispatch(NodeSideEvent::CAPTUREDDATAREQUEST, $event);
        return new JsonResponse($persistence_message);
    }

    public function gatewayIdAction($gateway_id, $limit) {
        $retrieve = $this->get('retrieve_senseddata');
        $gateway = $retrieve->retrieveGatewayRecords($gateway_id, $limit);
        $gateway_packet = array("gateway_id" => $gateway_id);
        return new JsonResponse(array_merge($gateway_packet, $gateway));
    }

    public function gatewayNodesAction($gateway_id, $limit) {
        $retrieve = $this->get('retrieve_senseddata');
        $gateway_sensors = $retrieve->retrieveGatewayNodes($gateway_id,
            $limit);
        $gateway_packet = array("gateway_id" => $gateway_id);
        return new JsonResponse(array_merge($gateway_packet, $gateway_sensors));
    }

    public function nodeDetailsAction($node_id, $limit) {
        $retrieve = $this->get('retrieve_senseddata');
        $sensor_info = $retrieve->retrieveNode($node_id, $limit);
        return new JsonResponse(($sensor_info));
    }
}
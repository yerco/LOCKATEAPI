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

    public function gatewayNodesAction($gateway_id, $node_id, $limit) {
        $retrieve = $this->get('retrieve_senseddata');
        $gateway_sensors = $retrieve->retrieveGatewayNodes($gateway_id, $node_id, $limit);
        $gateway_packet = array("gateway_id" => $gateway_id);
        return new JsonResponse(array_merge($gateway_packet, $gateway_sensors));
    }

    public function nodeDetailsAction($gateway_id, $node_id, $sensor_id, $limit) {
        $retrieve = $this->get('retrieve_senseddata');
        $sensor_info = $retrieve->retrieveNode($gateway_id, $node_id,
            $sensor_id, $limit);
        return new JsonResponse(($sensor_info));
    }

    /**
     * @param $gateway_id - int
     * @param $node_id - int
     * @param $sensor_id - int
     * @param $start_time - string YYYY-MM-SS_HHMMSS
     * @param $end_time - string YYYY-MM-SS_HHMMSS
     * @param $limit - string YYYY-MM-SS_HHMMSS
     *
     * @return JsonResponse
     */
    public function gatewayTimeAction($gateway_id, $node_id, $sensor_id,
        $start_time, $end_time, $limit) {
        $start = explode("_", $start_time);
        $start_date = $start[0];
        $starter = str_split($start[1], 2);
        $start_hour = $starter[0];
        $start_minute = $starter[1];
        $start_second = $starter[2];
        $end = explode("_", $end_time);
        $end_date = $end[0];
        $ender = str_split($end[1], 2);
        $end_hour = $ender[0];
        $end_minute = $ender[1];
        $end_second = $ender[2];
        $retrieve = $this->get('retrieve_senseddata');
        $gateway_info = $retrieve->retrieveRecordsGatewayTime($gateway_id,
            $node_id, $sensor_id,
            $start_date, $start_hour, $start_minute, $start_second,
            $end_date, $end_hour, $end_minute, $end_second, $limit
        );

        return new JsonResponse($gateway_info);
    }

    public function lastGatewayEventsAction($gateway_id, $limit) {
        $retrieve = $this->get('retrieve_senseddata');
        $last_events = $retrieve->retrieveLastGatewayEvents($gateway_id, $limit);
        $response = new JsonResponse($last_events);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}


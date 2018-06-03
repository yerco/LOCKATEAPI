<?php

namespace Lockate\APIBundle\EventListener;

use Lockate\APIBundle\Event\NodeSideEvent;
use Psr\Log\LoggerInterface;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CapturedDataRequestListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /* dev, prod settings */
    const FORWARD_URI_DEV = 'http://localhost/app_dev.php';
    const FORWARD_URI_PROD = 'http://lockate.hopto.org';
    const FORWARD_URI_DOCKER = 'http://lockate_site_webserver:80';

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $this->logger->info("Este es un mensaje");
        $request = $event->getRequest();
        $user_agent = $request->headers->get('User-Agent');
        $this->logger->info("The custom header value is: " . $user_agent);

//        if (rand(0, 100) > 50) {
//            $response = new Response('Come back later');
//            $event->setResponse($response);
//        }

        $isMac = strpos($user_agent, 'mac') !== false;
        // $isMac se le pasa a la template
        $request->attributes->set('isMac', $isMac);
//        $request->attributes->set('_controller', function() {
//            return new Response('Hola socito');
//        });
    }

    /**
     * @param NodeSideEvent $event
     * ($event->getJSON corresponds to `$request->getContent()`)
     */
    public function onCapturedDataRequest(NodeSideEvent $event){
        // Check monolog configuration
        $this->logger->debug("Request to forward received: " . time());
        //self::sendRequestToWebsite(self::FORWARD_URI_DEV, $event->getJSON());
        //self::sendRequestToWebsite(self::FORWARD_URI_PROD, $event->getJSON());
        self::sendRequestToWebsite(self::FORWARD_URI_DOCKER, $event->getJSON());
        $event->stopPropagation();
    }

    private function sendRequestToWebsite($url, $json_string) {
        $url = $url . '/site';
        $username = "uno";
        $password = "uno";
        $client = new Client();
        $options= array(
            'auth' => [
                $username,
                $password
            ],
            'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],
            'body' => $json_string,
            "debug" => true
        );
        try {
            $response = $client->post($url, $options);
            //var_dump($response->getBody()->getContents());
        } catch (ClientException $e) {
            echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                echo $e->getResponse() . "\n";
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            //'kernel.request' => 'onKernelRequest'
            //KernelEvents::REQUEST => 'onKernelRequest'
            NodeSideEvent::CAPTUREDDATAREQUEST => 'onCapturedDataRequest'
        );
    }
}

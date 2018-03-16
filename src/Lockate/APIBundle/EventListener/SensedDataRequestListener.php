<?php

namespace Lockate\APIBundle\EventListener;

use Lockate\APIBundle\Event\SensorSideEvent;
use Psr\Log\LoggerInterface;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SensedDataRequestListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

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

    public function onSensedDataRequest(SensorSideEvent $event){
        // Check monolog configuration
        $this->logger->debug("Request at: " . time());
        $event->stopPropagation();
    }

    public static function getSubscribedEvents()
    {
        return array(
            //'kernel.request' => 'onKernelRequest'
            //KernelEvents::REQUEST => 'onKernelRequest'
            SensorSideEvent::SENSEDDATAREQUEST => 'onSensedDataRequest'
        );
    }
}
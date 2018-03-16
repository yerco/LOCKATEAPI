<?php

namespace Lockate\APIBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SensorSideEvent extends Event
{
    const SENSEDDATAREQUEST = 'senseddata.request';

    protected $json_sent;

    public function __construct($json_sent) {
        $this->json_sent = $json_sent;
    }

    public function getJSON() {
        return $this->json_sent;
    }
}
<?php

namespace Lockate\APIBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class NodeSideEvent extends Event
{
    const CAPTUREDDATAREQUEST = 'captureddata.request';

    protected $json_sent;

    public function __construct($json_sent) {
        $this->json_sent = $json_sent;
    }

    public function getJSON() {
        return $this->json_sent;
    }
}
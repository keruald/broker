<?php

namespace Keruald\Broker;

abstract class Broker {
    abstract public function sendMessage ($message);
}

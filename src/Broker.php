<?php

namespace Keruald\Broker;

abstract class Broker {
    abstract public function sendMessage ($message);

    abstract static public function makeFromConfig ($params);
}

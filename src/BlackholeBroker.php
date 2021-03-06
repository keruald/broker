<?php

namespace Keruald\Broker;

class BlackholeBroker extends Broker implements BuildableFromConfigInterface {
    ///
    /// Broker implementation
    ///

    /**
     * Sends a message to the broker, which will silently discards it.
     *
     * @param string $message The message to send
     */
    public final function sendMessage ($message) {
        return $this;
    }

    /**
     * Determines if the configuration wants an omnipotent blackhole
     *
     * @param array $params An array with omnipotence information
     * @return bool
     */
    static private function configWantsOmnipotence ($params) {
        return array_key_exists('omnipotence', $params)
               &&
               $params['omnipotence'];
    }

    /**
     * Initializes a new instance of the broker from specified parameters
     *
     * @param array $params An array with omnipotence information
     * @return Keruald\Broker\BlackholeBroker A connected instance of the broker
     */
    static public function makeFromConfig ($params) {
        $instance = new self;

        if (self::configWantsOmnipotence($params)) {
            $instance->acceptAllMethodCalls();
        }

        return $instance;
    }

    ///
    /// Omnipotence to ease lightweight mock testing without any lib
    ///

    /**
     * @var bool
     */
    private $acceptAllMethodCalls = false;

    /**
     * Configures the broker to accept every method call
     */
    public function acceptAllMethodCalls () {
        $this->acceptAllMethodCalls = true;
        return $this;
    }

    /**
     * Handles a method overloading call
     *
     * @param string $name The name of the method being called
     * @param array $arguments An enumerated array containing the parameters
     *                         passed to the method
     */
    public function __call ($name, array $arguments) {
        if ($this->acceptAllMethodCalls) {
            return $this; // Brokers are intended to be fluent
        }

        throw new \BadFunctionCallException(
            "Blackhole broker doesn't implement the $name method."
        );
    }
}

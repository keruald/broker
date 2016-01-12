<?php

namespace Keruald\Broker;

class BrokerFactory {
    /**
     * Gets the map drivers <--> class
     *
     * @return array
     */
    public static function getDrivers () {
        return require 'drivers.php';
    }

    /**
     * Makes a new broker instance
     *
     * @param array $params The parameters
     * @return Keruald\Broker\Broker
     *
     * @throws InvalidArgumentException when $params doesn't contain a valid
     *                                  driver entry
     */
    public static function make ($params) {
        if (!array_key_exists('driver', $params)) {
            throw new \InvalidArgumentException(
                "Required parameter missing: driver"
            );
        }
        $driver = $params['driver'];

        $drivers = self::getDrivers();
        if (!array_key_exists($driver, $drivers)) {
            throw new \InvalidArgumentException(
                "Broker driver not found: $driver"
            );
        }
        $class = 'Keruald\Broker\\' . $drivers[$driver];

        return $class::makeFromConfig($params);
    }
}

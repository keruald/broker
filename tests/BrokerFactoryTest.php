<?php

namespace Keruald\Broker\Tests;

use Keruald\Broker\BrokerFactory;

class BrokerFactoryTest extends TestCase {

    public function testValidParameters () {
        $broker = BrokerFactory::make([
            'driver' => 'blackhole'
        ]);
        $this->assertInstanceOf('Keruald\Broker\BlackholeBroker', $broker);

        $broker = BrokerFactory::make([
            'driver' => 'amqp'
        ]);
        $this->assertInstanceOf('Keruald\Broker\AMQPBroker', $broker);
    }

    public function testOmnipotenceBlackhole () {
        $broker = BrokerFactory::make([
            'driver' => 'blackhole',
            'omnipotence' => true,
        ]);
        $broker->spreadLove(); // a method not in Broker abstract class
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyParameters () {
        BrokerFactory::make([]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidParameters () {
        BrokerFactory::make([
            'foo' => 'bar'
        ]);
    }
}

<?php

namespace Keruald\Broker\Tests;

use Keruald\Broker\AMQPBroker;

class AMQPBrokerTest extends TestCase {
    /**
     * @var Keruald\Broker\AMQPBroker
     */
    protected $instance;

    protected function setUp() {
        $this->instance = new AMQPBroker();
    }

    public function testFluencyPattern () {
        $methodsCascade = [
            ['connect'],
            ['setExchangeTarget', 'foo'],
            ['setQueueTarget', 'foo'],
            ['routeTo', 'bar'],
            ['sendMessage', 'lorem ipsum dolor'],
            ['disconnect'],
        ];

        $this->assertMethodCascading(
            $this->instance,
            $methodsCascade
        );
    }
}

<?php

namespace Keruald\Broker\Tests;

use Keruald\Broker\BlackholeBroker;

class BlackholeBrokerTest extends TestCase {
    /**
     * @var Keruald\Broker\BlackholeBroker
     */
    protected $instance;

    protected function setUp() {
        $this->instance = new BlackholeBroker();
    }

    /**
     * @expectedException \BadFunctionCallException
     */
    public function testNonDefaultOmnipotence () {
        // By default, our blackhole broker shouldn't accept any method.
        $this->instance->spreadLove();
    }

    public function testFluencyPattern () {
        $methodsCascade = [
            ['connect'],
            ['sendMessage', 'lorem ipsum dolor'],
            ['disconnect'],
        ];

        $this->instance->acceptAllMethodCalls();
        $this->assertMethodCascading(
            $this->instance,
            $methodsCascade
        );
    }
}

<?php declare(strict_types=1);

namespace Keruald\Broker\Tests;

use BadFunctionCallException;
use Keruald\Broker\BlackholeBroker;

class BlackholeBrokerTest extends TestCase {
    /**
     * @var Keruald\Broker\BlackholeBroker
     */
    protected $instance;

    protected function setUp() : void {
        $this->instance = new BlackholeBroker();
    }

    public function testNonDefaultOmnipotence () {
        // By default, our blackhole broker shouldn't accept any method.
        $this->expectException(BadFunctionCallException::class);

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

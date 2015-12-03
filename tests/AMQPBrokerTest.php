<?php

use Keruald\Broker\AMQPBroker;

class AMQPBrokerTest extends PHPUnit_Framework_TestCase {
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

    /**
     * Asserts the specified object follows a method cascading pattern
     *
     * @param object $instance the object to tests
     * @param Array $methodsCascade an array, each item an array following the call_user_func_array format
     */
    protected function assertMethodCascading ($instance, $methodsCascade) {
        //We determine the type of the object when first passed
        //to our test, then we call one par one each method,
        //each time verifying the returned type is still the same

        $type = get_class($instance);

        foreach ($methodsCascade as $method) {
            $methodName = array_shift($method);
            $instance = call_user_func_array(
                [$instance, $methodName],
                $method
            );
            $this->assertInstanceOf(
                $type,
                $instance,
                "$type::$methodName should return \$this"
            );
        }
    }
}

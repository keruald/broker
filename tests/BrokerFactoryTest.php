<?php declare(strict_types=1);
namespace Keruald\Broker\Tests;

use InvalidArgumentException;
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
        $this->expectNotToPerformAssertions();

        $broker = BrokerFactory::make([
            'driver' => 'blackhole',
            'omnipotence' => true,
        ]);
        $broker->spreadLove(); // a method not in Broker abstract class
    }

    public function testEmptyParameters () {
        $this->expectException(InvalidArgumentException::class);

        BrokerFactory::make([]);
    }

    public function testInvalidParameters () {
        $this->expectException(InvalidArgumentException::class);

        BrokerFactory::make([
            'foo' => 'bar'
        ]);
    }
}

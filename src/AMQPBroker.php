<?php

namespace Keruald\Broker;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPBroker extends Broker implements BuildableFromConfigInterface {

    ///
    /// Private members
    ///

    /**
     * @var PhpAmqpLib\Connection\AMQPStreamConnection
     */
    private $connection = null;

    /**
     * @var PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel = null;

    /**
     * The routing key, for topic exchange
     *
     * @var string
     */
    private $routingKey = '';

    /**
     * The target name
     */
    private $targetName = '';

    ///
    /// Constructor, destructor
    ///

    /**
     * Cleans up resources
     */
    public function __destruct() {
        $this->disconnect();
    }

    /**
     * Gets the default values for connect method
     *
     * @return array
     */
    private static function getDefaultValues () {
        return [
            'host' => 'localhost',
            'port' => 5672,
            'username' => 'guest',
            'password' => 'guest',
            'vhost' => '/',
        ];
    }

    /**
     * Initializes a new instance of the broker from specified parameters
     *
     * @param array $params An array with connect information
     * @return Keruald\Broker\AMQPBroker A connected instance of the broker
     */
    static public function makeFromConfig ($params) {
        $instance = new self;

        $params += self::getDefaultValues();

        $instance->connect(
            $params['host'],
            $params['port'],
            $params['username'],
            $params['password'],
            $params['vhost']
        );

        return $instance;
    }

    ///
    /// Connection
    ///

    /**
     * Connects to the message broker
     *
     * @param string $host The broker host
     * @param int $port The broker port
     * @param string $username The broker username
     * @param string $password The broker password
     * @param string $vhost The broker vhost
     */
    public function connect (
        $host = 'localhost',
        $port = 5672,
        $username = 'guest',
        $password = 'guest',
        $vhost = '/'
    ) {
        $this->connection = new AMQPStreamConnection(
            $host, $port,
            $username, $password,
            $vhost
        );
        $this->channel = $this->connection->channel();
        return $this;
    }

    /**
     * Disconnects from the message broker
     */
    public function disconnect () {
        if ($this->connection !== null) {
            $this->channel->close();
            $this->connection->close();
        }
        return $this;
    }

    ///
    /// Target methods
    ///

    /**
     * Sets an exchange point as a target to publish messages to
     *
     * @param string $name The exchange name
     * @param string $type The exchange type (direct, fanout, topic, headers)
     * @param bool $durable Indicates if the durable feature should be enabled
     */
    public function setExchangeTarget ($name, $type = 'topic',
                                       $durable = false) {
        $this->targetName = $name;
        $this->channel->exchange_declare(
            $name,
            $type,
            false, $durable, false // don't autodelete this target
        );
        return $this;
    }

    /**
     * Sets a queue as a target to publish messages to
     *
     * @param string $name The name of the queue
     */
    public function setQueueTarget ($name) {
        $this->targetName = $name;
        $this->channel->queue_declare(
            $name,
            false, false, false, false // don't autodelete this target
        );
        return $this;
    }

    ///
    /// Message methods
    ///

    /**
     * Sets the routing key
     *
     * @param string $key the routing key
     */
    public function routeTo ($key) {
        $this->routingKey = $key;
        return $this;
    }

    /**
     * Sends a message to the specified target queue or exchange
     *
     * @param string $message The message to send
     */
    public function sendMessage ($message) {
        $this->channel->basic_publish(
            new AMQPMessage($message),
            $this->targetName,
            $this->routingKey
        );
        return $this;
    }
}

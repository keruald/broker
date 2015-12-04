# Keruald Broker

Abstraction library to use message brokers.

Current focus is to prepare a reference AMQP implementation.

## Sample code

You can initialize an instance of the class, then use the fluency pattern
to cascade your instructions to connect to the server, parameter the target
and then send message.

For example, to send a message to the RabbitMQ 'haiku' exchange point,
with a specific routing key to allow consumers to see if they're interested
by the message:


```
$broker = new AMQPBroker();
$broker
    ->connect()
    ->setExchangeTarget('haiku')
    ->routeTo('basho.oldpond')
    ->sendMessage('古池や蛙飛び込む水の音');
```

## Add an implementation for another broker

We're interested to solve the target problem. We wanted to initially offer
both Exchange and Queue classes, but brokers treat them differently.

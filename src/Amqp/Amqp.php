<?php

namespace App\Amqp;

use AMQPChannel;
use AMQPConnection;
use AMQPExchange;
use AMQPQueue;
use Exception;

class Amqp
{
    private AMQPConnection $connection;
    private AMQPChannel $channel;

    public function __construct(
        private readonly string $amqpDsn
    ) {
        $parsedUrl = parse_url($this->amqpDsn);

        $this->connection = new AMQPConnection();
        $this->connection->setHost($parsedUrl['host'] ?? 'localhost');
        $this->connection->setPort($parsedUrl['port'] ?? 5672);
        $this->connection->setLogin($parsedUrl['user'] ?? '');
        $this->connection->setPassword($parsedUrl['pass'] ?? '');
        $this->connection->setVhost($parsedUrl['vhost'] ?? '/');
        $this->connection->connect();

        $this->channel = new AMQPChannel($this->connection);
        $this->channel->confirmSelect();
        $this->channel->setConfirmCallback(
            static fn (): bool => false,
            static fn () => throw new Exception('Message publication failed due to a negative acknowledgment (nack) from the broker.'),
        );
    }

    public function createExchange(string $name): AMQPExchange
    {
        $exchange = new AMQPExchange($this->channel);
        $exchange->setName($name);
        $exchange->setType(AMQP_EX_TYPE_TOPIC);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();
        return $exchange;
    }

    public function createQueue(string $name): AMQPQueue
    {
        $queue = new AMQPQueue($this->channel);
        $queue->setName($name);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();
        return $queue;
    }

    public function waitForConfirm(float $waitTime): void
    {
        $this->channel->waitForConfirm($waitTime);
    }

    public function disconnect(): void
    {
        $this->connection->disconnect();
    }
}

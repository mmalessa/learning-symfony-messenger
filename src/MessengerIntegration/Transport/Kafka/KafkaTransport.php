<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Transport\Kafka;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class KafkaTransport implements TransportInterface
{
    private KafkaSender $sender;

    private KafkaReceiver $receiver;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly RdKafkaFactory $rdKafkaFactory,
        private readonly KafkaSenderProperties $kafkaSenderProperties,
        private readonly KafkaReceiverProperties $kafkaReceiverProperties,
        private readonly string $role,
    ) {
    }

    public function get(): iterable
    {
        if (!in_array($this->role, ['receiver', ''] )) {
            throw new TransportException('The Kafka receiver is disabled.');
        }

        return $this->getReceiver()->get();
    }

    public function ack(Envelope $envelope): void
    {
        $this->getReceiver()->ack($envelope);
    }

    public function reject(Envelope $envelope): void
    {
        $this->getReceiver()->reject($envelope);
    }

    public function send(Envelope $envelope): Envelope
    {
        if (!in_array($this->role, ['sender', ''] )) {
            throw new TransportException('The Kafka sender is disabled.');
        }
        return $this->getSender()->send($envelope);
    }

    private function getSender(): KafkaSender
    {
        return $this->sender ?? $this->sender = new KafkaSender(
            $this->logger,
            $this->serializer,
            $this->rdKafkaFactory,
            $this->kafkaSenderProperties
        );
    }

    private function getReceiver(): KafkaReceiver
    {
        return $this->receiver ?? $this->receiver = new KafkaReceiver(
            $this->logger,
            $this->serializer,
            $this->rdKafkaFactory,
            $this->kafkaReceiverProperties
        );
    }
}

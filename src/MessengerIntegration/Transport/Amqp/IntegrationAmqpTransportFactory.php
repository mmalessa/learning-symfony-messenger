<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Transport\Amqp;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\Connection;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class IntegrationAmqpTransportFactory implements TransportFactoryInterface
{
    public function createTransport(
        #[\SensitiveParameter] string $dsn,
        array $options,
        SerializerInterface $serializer
    ): TransportInterface
    {
        unset($options['transport_name']);

        return new IntegrationAmqpTransport(Connection::fromDsn($dsn, $options), $serializer);
    }

    public function supports(#[\SensitiveParameter] string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'integration-amqp://')
            || str_starts_with($dsn, 'integration-amqps://');
    }
}

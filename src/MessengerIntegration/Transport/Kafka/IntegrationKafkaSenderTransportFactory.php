<?php

namespace App\MessengerIntegration\Transport\Kafka;

use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class IntegrationKafkaSenderTransportFactory
{
    public function createTransport(
        #[\SensitiveParameter] string $dsn,
        array $options,
        SerializerInterface $serializer
    ): TransportInterface
    {
        unset($options['transport_name']);

        return new IntegrationKafkaSenderTransport();
    }

    public function supports(#[\SensitiveParameter] string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'integration-kafka://');
    }
}

<?php

namespace App\MessengerIntegration\Transport\Http;

use App\MessengerIntegration\Message\SchemaIdMapperInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class IntegrationHttpTransportSenderFactory
{
    public function __construct(
        private readonly SchemaIdMapperInterface $schemaIdMapper,
        private readonly SerializerInterface     $serializer,
        private readonly LoggerInterface         $logger,
        private readonly array                   $externalSystemEndpointsPrefixMap,
    ) {
    }

    public function createTransport(
        #[\SensitiveParameter] string $dsn,
        array $options,
        SerializerInterface $serializer
    ): TransportInterface
    {
        unset($options['transport_name']);

        return new IntegrationHttpSenderTransport(
            $this->schemaIdMapper,
            $this->serializer,
            $this->logger,
            $this->externalSystemEndpointsPrefixMap,
        );
    }

    public function supports(#[\SensitiveParameter] string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'integration-http://')
            || str_starts_with($dsn, 'integration-https://');
    }
}

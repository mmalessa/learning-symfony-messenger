<?php

namespace App\MessengerIntegration\Transport\Http;

use App\MessengerIntegration\Message\HttpMessageMapperInterface;
use App\MessengerIntegration\Message\SchemaIdMapperInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class IntegrationHttpTransportFactory
{
    public function __construct(
        private readonly SchemaIdMapperInterface    $schemaIdMapper,
        private readonly HttpMessageMapperInterface $httpMessageMapper,
        private readonly SerializerInterface        $serializer,
        private readonly HttpClientFactory          $httpClientFactory,
        private readonly LoggerInterface            $logger,
    ) {
    }

    public function createTransport(
        #[\SensitiveParameter] string $dsn,
        array $options,
        SerializerInterface $serializer
    ): TransportInterface
    {
        unset($options['transport_name']);

        return new IntegrationHttpTransport(
            $this->serializer,
            $this->schemaIdMapper,
            $this->httpMessageMapper,
            $this->httpClientFactory,
            $this->logger,
        );
    }

    public function supports(#[\SensitiveParameter] string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'integration-http://')
            || str_starts_with($dsn, 'integration-https://');
    }
}

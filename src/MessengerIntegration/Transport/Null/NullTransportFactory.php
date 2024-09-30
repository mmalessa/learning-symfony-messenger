<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Transport\Null;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class NullTransportFactory implements TransportFactoryInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    )
    {
    }

    public function supports(#[\SensitiveParameter] string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'null://');
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new NullTransport($this->logger);
    }
}

<?php

namespace App\MessengerIntegration\Inbox;

use App\MessengerIntegration\Serializer\IntegrationSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class InputInboxService
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly IntegrationSerializer $integrationSerializer,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function processFromHttp(string $body, array $headers): void
    {
        $this->logger->debug("InputInboxService -> processFrom Http");

        //TODO - add some validation and checks here

        $envelope = $this->integrationSerializer->decode([
            'body' => $body,
            'headers' => $headers,
        ]);

        $stamps = [
            new ReceivedStamp('unknown_transport')
        ];

        // dispatch to default messagebus
        $this->messageBus->dispatch($envelope, $stamps);
    }
}

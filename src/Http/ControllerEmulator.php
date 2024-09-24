<?php

namespace App\Http;

use App\Message\DoSomethingFromExt;
use App\MessengerIntegration\Inbox\InputInboxService;
use App\MessengerIntegration\Serializer\IntegrationStamps\IntegrationStampsSerializerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ControllerEmulator
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly InputInboxService $inputInboxService,
    ) {
    }

    public function requestTestMessage()
    {
        $this->logger->debug("Request test message");

        // from request
        $body = json_encode((new DoSomethingFromExt('Some test content via HTTP'))->serialize(), JSON_THROW_ON_ERROR);
        $headers = [
            IntegrationStampsSerializerInterface::SCHEMA_ID_KEY => DoSomethingFromExt::schemaId(),
            IntegrationStampsSerializerInterface::MESSAGE_ID_KEY => Uuid::uuid7()->toString(),
        ];

        $this->inputInboxService->processFromHttp($body, $headers);
    }
}

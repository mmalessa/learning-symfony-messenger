<?php

namespace App\Http;

use App\Message\IncomingExternal\StartProcess;
use App\MessengerIntegration\Inbox\InputInboxService;
use App\MessengerIntegration\Message\IntegrationMessageAttributeStorage;
use App\MessengerIntegration\Serializer\IntegrationStamps\IntegrationStampsSerializerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class FakeController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly InputInboxService $inputInboxService,
        private readonly IntegrationMessageAttributeStorage $messageAttributeStorage,
    ) {
    }

    public function requestStartProcess()
    {
        $this->logger->debug("Request start process");

        // from request
        $body = json_encode((new StartProcess('Some test content via HTTP'))->serialize(), JSON_THROW_ON_ERROR);
        $headers = [
            IntegrationStampsSerializerInterface::SCHEMA_ID_KEY => $this->messageAttributeStorage->getByClassName(StartProcess::class)->schemaId,
            IntegrationStampsSerializerInterface::MESSAGE_ID_KEY => Uuid::uuid7()->toString(),
        ];

        $this->inputInboxService->processFromHttp($body, $headers);
    }
}

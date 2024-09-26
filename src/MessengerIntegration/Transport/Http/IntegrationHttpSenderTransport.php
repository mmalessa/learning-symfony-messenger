<?php

namespace App\MessengerIntegration\Transport\Http;

use App\Message\IntegrationMessageInterface;
use App\MessengerIntegration\Message\IntegrationMessageAttributeStorageInterface;
use App\MessengerIntegration\Serializer\IntegrationStamps\IntegrationStampsSerializerInterface;
use App\MessengerIntegration\Stamp\MessageIdStamp;
use App\MessengerIntegration\Stamp\SchemaIdStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\SetupableTransportInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;


// TODO
class IntegrationHttpSenderTransport implements TransportInterface, SetupableTransportInterface, MessageCountAwareInterface
{
    public function __construct(
        private IntegrationMessageAttributeStorageInterface $integrationMessageAttributeStorage,
        private SerializerInterface $serializer,
        private LoggerInterface $logger,
        private array $externalSystemEndpointsPrefixMap,
    ) {
    }

    public function setup(): void
    {
    }

    public function send(Envelope $envelope): Envelope
    {
        // FIXME - add some validation(s)
        $message = $envelope->getMessage();
        $messageClassName = get_class($message);
        $messageId = $envelope->last(MessageIdStamp::class)?->messageId;
        $messageAttributes = $this->integrationMessageAttributeStorage->getByClassName($messageClassName);
        $schemaId = $messageAttributes->schemaId;
        $endpointName = $messageAttributes->endpointName ?? null;
        $endpointPath = $messageAttributes->endpointPath ?? null;
        $endpointPrefix = $this->externalSystemEndpointsPrefixMap[$endpointName] ?? null;
        $endpointUri = sprintf(
            "%s/%s",
            rtrim($endpointPrefix ?? '', '/'),
            ltrim($endpointPath ?? '', '/')
        );

        $encodedEnvelope = $this->serializer->encode($envelope);
        // TODO - is it ok? vs ->encode($envelope->with(SchemaIdStamp($schmaId))
        $encodedEnvelope['headers'][IntegrationStampsSerializerInterface::SCHEMA_ID_KEY] = $schemaId;

        $this->logger->debug(
            "Sending integration HTTP message",
            [
                'schemaId' => $schemaId,
                'messageId' => $messageId,
                'className' => $messageClassName,
                'endpointName' => $endpointName,
                'endpointPrefix' => $endpointPrefix,
                'endpointPath' => $endpointPath,
                'endpointUri' => $endpointUri,
                'body' => $encodedEnvelope['body'],
                'headers' => json_encode($encodedEnvelope['headers'], JSON_THROW_ON_ERROR),
            ]
        );

        //TODO - add real API request

//        throw new TransportException('Transport test exception');
        return $envelope;
    }

    public function ack(Envelope $envelope): void
    {
    }

    public function get(): iterable
    {
        throw new TransportException('Not implemented');
    }

    public function getMessageCount(): int
    {
        return 0;
    }

    public function reject(Envelope $envelope): void
    {
    }
}

<?php

namespace App\MessengerIntegration\Transport\Http;

use App\MessengerIntegration\Message\HttpMessageMapperInterface;
use App\MessengerIntegration\Message\SchemaIdMapperInterface;
use App\MessengerIntegration\Stamp\MessageIdStamp;
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
        private SerializerInterface     $serializer,
        private SchemaIdMapperInterface $integrationMessageAttributeStorage,
        private HttpMessageMapperInterface $httpMessageMapper,
        private LoggerInterface         $logger,
    ) {
    }

    public function setup(): void
    {
    }

    public function send(Envelope $envelope): Envelope
    {
        // TODO - add some validation(s)
        $message = $envelope->getMessage();
        $messageClassName = get_class($message);
        $messageId = $envelope->last(MessageIdStamp::class)?->messageId;
        $schemaId = $this->integrationMessageAttributeStorage->getSchemaIdByClassName($messageClassName);

        $endpointUrl = $this->httpMessageMapper->getUrlByClassName($messageClassName);

        $encodedEnvelope = $this->serializer->encode($envelope);

        $this->logger->debug(
            "Sending integration HTTP message",
            [
                'schemaId' => $schemaId,
                'messageId' => $messageId,
                'className' => $messageClassName,
                'endpointUrl' => $endpointUrl,
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

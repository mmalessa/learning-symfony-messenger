<?php

namespace App\MessengerIntegration\Serializer;

use App\Message\IntegrationMessageInterface;
use App\MessengerIntegration\Message\SchemaIdMapperInterface;
use App\MessengerIntegration\Message\UnhandledMessage;
use App\MessengerIntegration\Serializer\Body\BodySerializerInterface;
use App\MessengerIntegration\Serializer\IntegrationStamps\IntegrationStampsSerializerInterface;
use App\MessengerIntegration\Serializer\MessengerStamps\MessengerStampsSerializerInterface;
use App\MessengerIntegration\Stamp\KafkaMessageKeyStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class IntegrationSerializer implements SerializerInterface
{
    public function __construct(
        private readonly MessengerStampsSerializerInterface   $messengerStampsSerializer,
        private readonly BodySerializerInterface              $bodySerializer,
        private readonly IntegrationStampsSerializerInterface $integrationStampsSerializer,
        private readonly LoggerInterface                      $logger,
        private readonly SchemaIdMapperInterface              $schemaIdMapper,
    ) {
    }

    public function encode(Envelope $envelope): array
    {
        $this->logger->info("*** Encode Envelope ***");
        $message = $envelope->getMessage();

        $schemaId = $this->schemaIdMapper->getSchemaIdByClassName(get_class($message));

        // MESSAGE BODY
        if (! ($message instanceof IntegrationMessageInterface)) {
            throw new \RuntimeException('Message is not a valid integration message.');
        }
        $body = $this->bodySerializer->serialize($message, $schemaId);

        // HEADERS
        $headers = array_merge(
            $this->messengerStampsSerializer->serialize(
                $envelope
                    ->withoutStampsOfType(ErrorDetailsStamp::class)
                    ->all()
            ),
            $this->integrationStampsSerializer->serialize(
                $envelope->all()
            ),
        );

        // ENCODED DATA
        $data = [
            'body' => $body,
            'headers' => $headers,
        ];

        // SOMETIMES ADD KAFKA MESSAGE KEY
        /** @var KafkaMessageKeyStamp|null $messageKeyStamp */
        $messageKeyStamp = $envelope->last(KafkaMessageKeyStamp::class);
        if (null !== $messageKeyStamp) {
            $data['key'] = $messageKeyStamp->messageKey;
        }

        return $data;
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        try {
            $this->logger->info("*** Decode Envelope ***");
            $body = $encodedEnvelope['body'];
            $headers = $encodedEnvelope['headers'];
            $kafkaMessageKey = $encodedEnvelope['key'] ?? null;

            // SCHEMA ID
            $schemaId = $this->integrationStampsSerializer->getSchemaIdFromHeaders($headers);
            if (null === $schemaId) {
                throw new \RuntimeException('SchemaId cannot be null');
            }
            $className = $this->schemaIdMapper->getClassNameBySchemaId($schemaId);

            // MESSAGE BODY
            $message = $this->bodySerializer->deserialize($body, $className);

            // STAMPS
            $stamps = array_merge(
                $this->messengerStampsSerializer->deserialize($headers ?? []),
                $this->integrationStampsSerializer->deserialize($headers ?? []),
            );

            // SOMETIMES ADD KAFKA MESSAGE KEY STAMP
            if (null !== $kafkaMessageKey) {
                $stamps[] = new KafkaMessageKeyStamp($kafkaMessageKey);
            }

            return new Envelope($message, $stamps);
        } catch (\Throwable $throwable) {
            $message = new UnhandledMessage($body, $headers, $throwable->getMessage());
            return Envelope::wrap($message);
        }

    }
}

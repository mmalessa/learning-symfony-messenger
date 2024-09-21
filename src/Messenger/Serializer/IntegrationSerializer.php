<?php

namespace App\Messenger\Serializer;

use App\Message\IntegrationMessageInterface;
use App\Messenger\Mapper\SchemaIdMapperInterface;
use App\Messenger\Serializer\Body\BodySerializerInterface;
use App\Messenger\Serializer\IntegrationStamps\IntegrationStampsSerializerInterface;
use App\Messenger\Serializer\MessengerStamps\MessengerStampsSerializerInterface;
use App\Messenger\Stamp\KafkaMessageKeyStamp;
use App\Messenger\Stamp\SchemaIdStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class IntegrationSerializer implements SerializerInterface
{
    public function __construct(
        private readonly SchemaIdMapperInterface              $schemaIdMapper,
        private readonly MessengerStampsSerializerInterface   $messengerStampsSerializer,
        private readonly BodySerializerInterface              $bodySerializer,
        private readonly IntegrationStampsSerializerInterface $integrationStampsSerializer,
    ) {
    }

    public function encode(Envelope $envelope): array
    {
        // SCHEMA ID
        $schemaIdStamp = $envelope->last(SchemaIdStamp::class) ?? null;
        if (null === $schemaIdStamp) {
            throw new \RuntimeException('SchemaIdStamp cannot be null');
        }
        $schemaId = $schemaIdStamp->schemaId;

        // MESSAGE BODY
        $message = $envelope->getMessage();
        if (! ($message instanceof IntegrationMessageInterface)) {
            throw new \RuntimeException('Message is not a valid integration message.');
        }
        $body = $this->bodySerializer->serialize($message, $schemaId);

        // HEADERS
        $headers = array_merge(
            $this->messengerStampsSerializer->serialize(
                $envelope->withoutStampsOfType(ErrorDetailsStamp::class)->all()
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
    }
}

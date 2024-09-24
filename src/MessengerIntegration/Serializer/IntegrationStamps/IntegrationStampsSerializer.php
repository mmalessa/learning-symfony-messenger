<?php

namespace App\MessengerIntegration\Serializer\IntegrationStamps;

use App\MessengerIntegration\Stamp\MessageIdStamp;
use App\MessengerIntegration\Stamp\SchemaIdStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

class IntegrationStampsSerializer implements IntegrationStampsSerializerInterface
{
    public function serialize(array $stamps): array
    {
        $headers = [];

        /** @var SchemaIdStamp|null $schemaIdStamp */
        $schemaIdStamp = $this->getLastStamp(SchemaIdStamp::class, $stamps);
        if (null !== $schemaIdStamp) {
            $headers[self::SCHEMA_ID_KEY] = $schemaIdStamp->schemaId;
        }

        /** @var MessageIdStamp|null $messageIdStamp */
        $messageIdStamp = $this->getLastStamp(MessageIdStamp::class, $stamps);
        if (null !== $messageIdStamp) {
            $headers[self::MESSAGE_ID_KEY] = $messageIdStamp->messageId;
        }

        return $headers;
    }

    public function deserialize(array $headers): array
    {
        $stamps = [];

        if (array_key_exists(self::SCHEMA_ID_KEY, $headers)) {
            $stamps[] = new SchemaIdStamp($headers[self::SCHEMA_ID_KEY]);
        }

        if (array_key_exists(self::MESSAGE_ID_KEY, $headers)) {
            $stamps[] = new MessageIdStamp($headers[self::MESSAGE_ID_KEY]);
        }

        return $stamps;
    }

    public function getSchemaIdFromHeaders(array $headers): ?string
    {
        return $headers[self::SCHEMA_ID_KEY] ?? null;
    }

    private function getLastStamp(string $stampFqcn, array $stamps): ?StampInterface
    {
        return isset($stamps[$stampFqcn]) ? end($stamps[$stampFqcn]) : null;
    }
}

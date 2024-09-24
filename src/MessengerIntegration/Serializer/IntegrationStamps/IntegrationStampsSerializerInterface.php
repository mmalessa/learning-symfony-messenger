<?php

namespace App\MessengerIntegration\Serializer\IntegrationStamps;

use App\MessengerIntegration\Stamp\KafkaMessageKeyStamp;

interface IntegrationStampsSerializerInterface
{
    public const string SCHEMA_ID_KEY = 'x-integration-schema-id';
    public const string MESSAGE_ID_KEY = 'x-integration-message-id';

    public function serialize(array $stamps): array;
    public function deserialize(array $headers): array;
    public function getSchemaIdFromHeaders(array $headers): ?string;
}

<?php

namespace App\Messenger\Serializer\Body;

use App\Message\IntegrationMessageInterface;

interface BodySerializerInterface
{
    public function serialize(IntegrationMessageInterface $message, string $schemaId): string;
    public function deserialize(string $message, string $className): IntegrationMessageInterface;
}

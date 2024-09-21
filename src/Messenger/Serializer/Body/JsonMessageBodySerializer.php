<?php

namespace App\Messenger\Serializer\Body;

use App\Message\IntegrationMessageInterface;

class JsonMessageBodySerializer implements BodySerializerInterface
{
    public function serialize(IntegrationMessageInterface $message, string $schemaId): string
    {
        $serializedMessage = $message->serialize();
        return json_encode($serializedMessage);
    }

    public function deserialize(string $message, string $className): IntegrationMessageInterface
    {
        $bodyArray = json_decode($message, true, 512, JSON_THROW_ON_ERROR);
        /** @var IntegrationMessageInterface $message */
        $message = $className::deserialize($bodyArray);
        return $message;
    }
}

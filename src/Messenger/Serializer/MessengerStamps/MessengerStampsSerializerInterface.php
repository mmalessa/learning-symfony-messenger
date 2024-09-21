<?php

namespace App\Messenger\Serializer\MessengerStamps;

interface MessengerStampsSerializerInterface
{
    public const string STAMP_FQCN_PREFIX = 'Symfony\\Component\\Messenger\\Stamp\\';
    public const string STAMP_PREFIX = 'x-messenger-stamp-';
    public const string FORMAT = 'json';
    public const array CONTEXT = ['messenger_serialization' => true];

    public function serialize(array $stamps): array;
    public function deserialize(array $headers): array;
}

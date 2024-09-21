<?php

namespace App\Messenger\Serializer\MessengerStamps;

use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Serializer\Serializer;

class MessengerStampsSerializer implements MessengerStampsSerializerInterface
{
    public function __construct(
        private readonly Serializer $symfonySerializer,
    ) {
    }

    public function serialize(array $stamps): array
    {
        $headers = [];
        foreach ($stamps as $stampFqcn => $stampList) {
            if (!str_starts_with($stampFqcn, self::STAMP_FQCN_PREFIX)) {
                continue;
            }

            $headerKey = sprintf("%s%s", self::STAMP_PREFIX, $stampFqcn);
            $headerValue = $this->symfonySerializer->serialize(
                $stampList,
                self::FORMAT,
                self::CONTEXT
            );
            $headers[$headerKey] = $headerValue;
        }

        return $headers;
    }

    public function deserialize(array $headers): array
    {
        $stamps = [];
        foreach ($headers as $headerKey => $headerValue) {
            if (!str_starts_with($headerKey, self::STAMP_PREFIX)) {
                continue;
            }
            $stampFqcn = substr($headerKey, strlen(self::STAMP_PREFIX));
            $stamps[] = $this->symfonySerializer->deserialize(
                $headerValue,
                sprintf('%s%s', $stampFqcn, '[]'),
                self::FORMAT,
                self::CONTEXT
            );
        }

        return array_merge(...$stamps);
    }
}

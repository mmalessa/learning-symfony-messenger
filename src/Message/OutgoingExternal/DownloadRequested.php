<?php

namespace App\Message\OutgoingExternal;

use App\Message\OutgoingKafkaMessageInterface;

class DownloadRequested implements OutgoingKafkaMessageInterface
{
    public function __construct(
        public readonly string $stamp,
    ) {
    }

    public function serialize(): array
    {
        return [
            'stamp' => $this->stamp,
        ];
    }

    public static function deserialize(array $data): self
    {
        return new self(
            $data['stamp'],
        );
    }

    public static function schemaId(): string
    {
        return 'mm.my.download_requested';
    }
}

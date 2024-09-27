<?php

namespace App\Message\OutgoingExternal;

use App\Message\OutgoingKafkaMessageInterface;
use App\MessengerIntegration\Message\Attribute\AsIntegrationMessage;

#[AsIntegrationMessage(schemaId: 'mm.my.download_requested')]
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
}

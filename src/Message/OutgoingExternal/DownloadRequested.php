<?php

namespace App\Message\OutgoingExternal;

use App\Message\OutgoingMessageInterface;
use App\MessengerIntegration\Message\Attribute\AsIntegrationMessage;
use App\MessengerIntegration\Message\Attribute\AsKafkaOutgoingMessage;

#[AsIntegrationMessage(schemaId: 'mm.my.download_requested')]
#[AsKafkaOutgoingMessage()]
class DownloadRequested implements OutgoingMessageInterface
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

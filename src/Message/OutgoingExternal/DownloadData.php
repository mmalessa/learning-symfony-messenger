<?php

namespace App\Message\OutgoingExternal;

use App\Message\OutgoingMessageInterface;
use App\MessengerIntegration\Message\Attribute\AsHttpOutgoingMessage;
use App\MessengerIntegration\Message\Attribute\AsIntegrationMessage;

#[AsIntegrationMessage(schemaId: 'mm.ext.download_data')]
#[AsHttpOutgoingMessage(endpointName: 'some_system', endpointPath: '/api/v2/download_data', method: 'POST')]
readonly class DownloadData implements OutgoingMessageInterface
{
    public function __construct(
        public string $messageContent
    ) {
    }

    public function serialize(): array
    {
        return [
            'messageContent' => $this->messageContent,
        ];
    }

    public static function deserialize(array $data): self
    {
        return new self(
            $data['messageContent']
        );
    }
}

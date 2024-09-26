<?php

namespace App\Message\OutgoingExternal;

use App\Message\OutgoingHttpMessageInterface;
use App\MessengerIntegration\Message\AsIntegrationMessage;

#[AsIntegrationMessage(schemaId: 'mm.ext.download_data', endpointPath: '/api/download_data', endpointName: 'some_system')]
readonly class DownloadData implements OutgoingHttpMessageInterface
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

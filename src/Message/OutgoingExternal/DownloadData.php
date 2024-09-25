<?php

namespace App\Message\OutgoingExternal;

use App\Message\OutgoingMessageInterface;

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

    public static function schemaId(): string{
        return 'mm.ext.download_data';
    }
}

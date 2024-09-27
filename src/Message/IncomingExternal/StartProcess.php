<?php

namespace App\Message\IncomingExternal;

use App\Message\IncomingMessageInterface;
use App\MessengerIntegration\Message\Attribute\AsIntegrationMessage;
use App\MessengerIntegration\Message\Attribute\AsTest;

#[AsTest]
#[AsIntegrationMessage(schemaId: 'mm.my.start_process')]
readonly class StartProcess implements IncomingMessageInterface
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

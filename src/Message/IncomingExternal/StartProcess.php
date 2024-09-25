<?php

namespace App\Message\IncomingExternal;

use App\Message\IncomingMessageInterface;

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

    public static function schemaId(): string{
        return 'mm.my.start_process';
    }
}

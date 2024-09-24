<?php

namespace App\Message;

class DoSomethingToExt implements OutgoingMessageInterface
{
    public function __construct(
        public readonly string $messageContent
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
        return 'mm.ext.do_sth';
    }
}

<?php

namespace App\Message;

interface IntegrationMessageInterface
{
    public static function schemaId(): string;
    public function serialize(): array;
    public static function deserialize(array $data): self;
}

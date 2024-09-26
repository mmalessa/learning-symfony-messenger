<?php

namespace App\Message;

interface IntegrationMessageInterface
{
    public function serialize(): array;
    public static function deserialize(array $data): self;
}

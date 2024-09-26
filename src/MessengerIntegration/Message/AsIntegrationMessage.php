<?php

namespace App\MessengerIntegration\Message;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsIntegrationMessage
{
    public string $className;

    public function __construct(
        public string $schemaId,
    ) {
    }

    public static function create(array $attributes): self
    {
        return new self(
            $attributes['schemaId'],
        );
    }
}

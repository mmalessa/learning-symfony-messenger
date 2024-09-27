<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AsIntegrationMessage
{
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

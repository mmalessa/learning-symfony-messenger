<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AsIntegrationMessage
{
    public function __construct(
        public string $schemaId,
        public ?string $className = null,
        public ?string $endpointPath = null,
        public ?string $endpointName = null,
    ) {
    }

    public static function create(array $attributes): self
    {
        return new self(
            $attributes['schemaId'],
            $attributes['className'] ?? null,
            $attributes['endpointPath'] ?? null,
            $attributes['endpointName'] ?? null
        );
    }
}

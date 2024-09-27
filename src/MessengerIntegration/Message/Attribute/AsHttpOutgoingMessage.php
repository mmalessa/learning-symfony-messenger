<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AsHttpOutgoingMessage
{
    public function __construct(
        public string $endpointName,
        public string $endpointPath,
    ) {
    }

    public static function create(array $attributes): self
    {
        return new self(
            $attributes['endpointName'],
            $attributes['endpointPath'],
        );
    }
}

<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AsKafkaOutgoingMessage
{
    public function __construct(
    ) {
    }

    public static function create(): self
    {
        return new self();
    }
}

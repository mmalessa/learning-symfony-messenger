<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AsKafkaOutgoingMessage
{
    public function __construct(
        public string $topicName,
    ) {
    }

    public static function create(array $attributes): self
    {
        return new self(
            $attributes['topicName'],
        );
    }
}

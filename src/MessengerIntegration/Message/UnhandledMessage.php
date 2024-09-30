<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

class UnhandledMessage
{
    public function __construct(
        public readonly string $body,
        public readonly array $headers,
        public readonly string $message,
    )
    {
    }
}

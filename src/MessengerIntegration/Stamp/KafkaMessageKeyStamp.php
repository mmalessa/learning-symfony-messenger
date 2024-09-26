<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class KafkaMessageKeyStamp implements StampInterface
{
    public function __construct(
        public readonly string $messageKey,
    ) {
    }
}

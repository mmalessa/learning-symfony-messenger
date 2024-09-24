<?php

namespace App\MessengerIntegration\Stamp;

class KafkaMessageKeyStamp implements IntegrationStampInterface
{
    public function __construct(
        public readonly string $messageKey,
    ) {
    }
}

<?php

namespace App\Messenger\Stamp;

class KafkaMessageKeyStamp implements IntegrationStampInterface
{
    public function __construct(
        public readonly string $messageKey,
    ) {
    }
}

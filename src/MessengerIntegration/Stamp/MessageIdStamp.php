<?php

namespace App\MessengerIntegration\Stamp;

class MessageIdStamp implements IntegrationStampInterface
{
    public function __construct(
        public readonly string $messageId
    ) {
    }
}

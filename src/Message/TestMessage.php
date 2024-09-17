<?php

namespace App\Message;

class TestMessage
{
    public function __construct(
        public readonly string $messageContent
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

interface KafkaMessageMapperInterface
{
    public function register(string $className): void;
    public function match(string $className): bool;
}

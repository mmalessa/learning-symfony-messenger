<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

class KafkaMessageMapper implements KafkaMessageMapperInterface
{
    private array $messages = [];

    public function register(string $className): void
    {
        $this->messages[$className] = [];
    }

    public function match(string $className): bool
    {
        return array_key_exists($className, $this->messages);
    }

    public function debug(): void
    {
        print_r($this->messages);
    }
}

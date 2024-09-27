<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

use App\MessengerIntegration\Message\Attribute\AsHttpOutgoingMessage;

interface HttpMessageMapperInterface
{
    public function register(string $className, array $attributes): void;
    public function getUrlByClassName(string $className): ?string;
}

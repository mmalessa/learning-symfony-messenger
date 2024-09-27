<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

class HttpMessageMapper implements HttpMessageMapperInterface
{
    public function __construct(
        private readonly array $prefixMap
    ) {
    }

    private array $messages = [];
    public function register(string $className, array $attributes): void
    {
        // TODO - add unique validation
        $this->messages[$className] = $attributes;
    }

    // TODO - throw exception or something
    public function getUrlByClassName(string $className): ?string
    {
        $attributes = $this->messages[$className] ?? null;
        if (null === $attributes) {
            return null;
        }
        $endpointName = $attributes['endpointName'];
        $endpointPath = $attributes['endpointPath'];
        $prefix = $this->prefixMap[$endpointName];

        return sprintf("%s/%s", rtrim($prefix, '/'), ltrim($endpointPath, '/'));
    }

    public function getMethodByClassName(string $className): ?string
    {
        return $this->messages[$className]['method'] ?? null;
    }

    public function getEndpointNameByClassName(string $className): ?string
    {
        return $this->messages[$className]['endpointName'] ?? null;
    }
}

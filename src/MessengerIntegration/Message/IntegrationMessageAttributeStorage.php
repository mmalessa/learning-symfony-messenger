<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

class IntegrationMessageAttributeStorage implements IntegrationMessageAttributeStorageInterface
{
    private array $classes = [];
    private array $ids = [];
    public function register(string $className, array $attributes): void
    {
        $attributes['className'] = $className; // force rewrite
        $attribute = AsIntegrationMessage::create($attributes);
        $this->classes[$attribute->className] = $attribute;
        $this->ids[$attribute->schemaId] = $attribute;
    }

    public function getByClassName(string $className): AsIntegrationMessage
    {
        // TODO - validation
        return $this->classes[$className];
    }

    public function getBySchemaId(string $schemaId): AsIntegrationMessage
    {
        // TODO - validation
        return $this->ids[$schemaId];
    }

    public function getDebug(): array
    {
        return array_merge($this->classes, $this->ids);
    }
}

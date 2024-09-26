<?php

namespace App\MessengerIntegration\Message;

class IntegrationMessageAttributeStorage
{
    private array $classes = [];
    private array $ids = [];
    public function register(string $className, array $attributes): void
    {
        $attribute = AsIntegrationMessage::create($attributes);
        $attribute->className = $className;
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

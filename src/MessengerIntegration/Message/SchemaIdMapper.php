<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

class SchemaIdMapper implements SchemaIdMapperInterface
{
    private array $classNames = [];
    private array $schemaIds = [];
    public function register(string $className, string $schemaId): void
    {
        // TODO - add unique validation
        $this->classNames[$className] = $schemaId;
        $this->schemaIds[$schemaId] = $className;
    }

    public function getSchemaIdByClassName(string $className): string
    {
        // TODO - validation
        return $this->classNames[$className];
    }

    public function getClassNameBySchemaId(string $schemaId): string
    {
        // TODO - validation
        return $this->schemaIds[$schemaId];
    }

    public function getDebug(): array
    {
        return array_merge($this->classNames, $this->schemaIds);
    }
}

<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

interface SchemaIdMapperInterface
{
    public function register(string $className, string $schemaId): void;
    public function getSchemaIdByClassName(string $className): string;
    public function getClassNameBySchemaId(string $schemaId): string;
}

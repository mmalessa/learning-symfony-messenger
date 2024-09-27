<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

interface SchemaIdMapperInterface
{
    public function getSchemaIdByClassName(string $className): string;
    public function getClassNameBySchemaId(string $schemaId): string;
}

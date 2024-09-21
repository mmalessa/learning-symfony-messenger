<?php

namespace App\Messenger\Mapper;

interface SchemaIdMapperInterface
{
    public function getClassNameBySchemaId(string $schemaId): string;
    public function getSchemaIdByClassName(string $className): string;
}

<?php

namespace App\MessengerIntegration\Mapper;

class InMemorySchemaIdMapper implements SchemaIdMapperInterface
{
    public function __construct(
        private readonly array $list
    ) {
    }

    public function getClassNameBySchemaId(string $schemaId): string
    {
        if (!array_key_exists($schemaId, $this->list)) {
            throw new \RuntimeException(sprintf('Schema id "%s" does not exist.', $schemaId));
        }
        return $this->list[$schemaId];
    }

    public function getSchemaIdByClassName(string $className): string
    {
        foreach ($this->list as $schemaId => $name) {
            if ($className === $name) {
                return $schemaId;
            }
        }
        throw new \RuntimeException(sprintf('Schema id "%s" does not exist.', $className));
    }
}

<?php

namespace App\Messenger\Stamp;

class SchemaIdStamp implements IntegrationStampInterface
{
    public function __construct(
        public readonly string $schemaId,
    ) {
    }
}

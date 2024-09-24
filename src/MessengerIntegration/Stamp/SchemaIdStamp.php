<?php

namespace App\MessengerIntegration\Stamp;

class SchemaIdStamp implements IntegrationStampInterface
{
    public function __construct(
        public readonly string $schemaId,
    ) {
    }
}

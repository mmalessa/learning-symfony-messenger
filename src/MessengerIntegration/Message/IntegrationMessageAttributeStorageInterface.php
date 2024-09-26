<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message;

interface IntegrationMessageAttributeStorageInterface
{
    public function getByClassName(string $className): AsIntegrationMessage;
    public function getBySchemaId(string $schemaId): AsIntegrationMessage;
}

<?php

declare(strict_types=1);

namespace App\MessengerIntegration\DependencyInjection;

use App\MessengerIntegration\Message\Attribute\AsHttpOutgoingMessage;
use App\MessengerIntegration\Message\Attribute\AsIntegrationMessage;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessengerIntegrationPass implements CompilerPassInterface
{


    public function process(ContainerBuilder $container): void
    {
        $this->registerSchemaIdsMessages($container);
        $this->registerHttpMessages($container);
        $this->registerKafkaMessages($container);
    }
    private function getAttributesByClassName(string $className, string $attributeClass): array
    {
        $reflection = new ReflectionClass($className);
        $attributes = $reflection->getAttributes($attributeClass);
        if (empty($attributes)) {
            throw new \RuntimeException(sprintf("FIXME 1 MESSAGE TAG: %s", $className));
        }
        return $attributes[0]->getArguments();
    }

    private function registerSchemaIdsMessages(ContainerBuilder $container): void
    {
        $mapperServices = $container->findTaggedServiceIds('messenger.integration.schema_id_mapper');
        $mapperServicesCount = count($mapperServices);
        if (1 !== $mapperServicesCount) {
            throw new \Exception(sprintf("SchemaIdMapper has defined %d service(s), should be exactly 1", $mapperServicesCount));
        }
        $mapperServiceId = array_keys($mapperServices)[0];
        $mapper = $container->getDefinition($mapperServiceId);

        $taggedServices = $container->findTaggedServiceIds('messenger.integration.message');
        foreach ($taggedServices as $className => $tagAttributes) {
            $attributes = $this->getAttributesByClassName($className, AsIntegrationMessage::class);
            $schemaId = $attributes['schemaId'];
            $mapper->addMethodCall('register', [$className, $schemaId]);
        }
    }

    private function registerHttpMessages(ContainerBuilder $container): void
    {
        $mapperServices = $container->findTaggedServiceIds('messenger.integration.http_message_mapper');
        $mapperServicesCount = count($mapperServices);
        if (1 !== $mapperServicesCount) {
            throw new \Exception(sprintf("HttpMessageMapper has defined %d service(s), should be exactly 1", $mapperServicesCount));
        }
        $mapperServiceId = array_keys($mapperServices)[0];
        $mapper = $container->getDefinition($mapperServiceId);

        $taggedServices = $container->findTaggedServiceIds('messenger.integration.http_message');
        foreach ($taggedServices as $className => $tagAttributes) {
            $attributes = $this->getAttributesByClassName($className, AsHttpOutgoingMessage::class);
            $mapper->addMethodCall('register', [$className, $attributes]);
        }
    }

    private function registerKafkaMessages(ContainerBuilder $container): void
    {
        $mapperServices = $container->findTaggedServiceIds('messenger.integration.kafka_message_mapper');
        $mapperServicesCount = count($mapperServices);
        if (1 !== $mapperServicesCount) {
            throw new \Exception(sprintf("KafkaMessageMapper has defined %d service(s), should be exactly 1", $mapperServicesCount));
        }
        $mapperServiceId = array_keys($mapperServices)[0];
        $mapper = $container->getDefinition($mapperServiceId);

        $taggedServices = $container->findTaggedServiceIds('messenger.integration.kafka_message');
        foreach ($taggedServices as $className => $tagAttributes) {
            $mapper->addMethodCall('register', [$className]);
        }
    }
}

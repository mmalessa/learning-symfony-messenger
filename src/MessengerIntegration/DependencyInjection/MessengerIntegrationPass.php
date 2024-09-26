<?php

declare(strict_types=1);

namespace App\MessengerIntegration\DependencyInjection;

use App\MessengerIntegration\Message\AsIntegrationMessage;
use App\MessengerIntegration\Message\IntegrationMessageAttributeStorage;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessengerIntegrationPass implements CompilerPassInterface
{


    public function process(ContainerBuilder $container): void
    {
        $storageServices = $container->findTaggedServiceIds('messenger.integration.storage');
        $storageServicesCount = count($storageServices);
        if (1 !== $storageServicesCount) {
            throw new \Exception(sprintf("FIXME 2 %d", $storageServicesCount));
        }
        $storageServiceId = array_keys($storageServices)[0];
        $storage = $container->getDefinition($storageServiceId);

        $taggedServices = $container->findTaggedServiceIds('messenger.integration.message');
        foreach ($taggedServices as $className => $tagAttributes) {
            $attributes = $this->getAttributesByClassName($className);
            $storage->addMethodCall('register', [$className, $attributes]);
        }
    }
    private function getAttributesByClassName(string $className): array
    {
        $reflection = new ReflectionClass($className);
        $attributes = $reflection->getAttributes(AsIntegrationMessage::class);
        if (empty($attributes)) {
            throw new \RuntimeException(sprintf("FIXME 1 MESSAGE TAG: %s", $className));
        }
        return $attributes[0]->getArguments();
    }
}

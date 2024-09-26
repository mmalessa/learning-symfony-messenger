<?php

declare(strict_types=1);

namespace App\MessengerIntegration\DependencyInjection;

use App\MessengerIntegration\Message\AsIntegrationMessage;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class MessengerIntegrationBundle extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->registerAttributeForAutoconfiguration(
            AsIntegrationMessage::class,
            function (ChildDefinition $definition) {
                $definition->addTag('messenger.integration.message');
            }
        );
    }
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessengerIntegrationPass());
    }
}

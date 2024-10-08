<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Koco\Kafka\KocoKafkaBundle::class => ['all' => true],
    App\MessengerIntegration\DependencyInjection\MessengerIntegrationBundle::class => ['all' => true],
];

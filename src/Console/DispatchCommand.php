<?php

declare(strict_types=1);

namespace App\Console;

use App\Message\TestMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

#[AsCommand(name: 'app:dispatch', description: 'Dispatch')]
class DispatchCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Dispatch");

        $this->messageBus->dispatch(
            new TestMessage('Message for Doctrine'),
            [
                new TransportNamesStamp(['app_doctrine_transport'])
            ]
        );

        $this->messageBus->dispatch(
            new TestMessage('Message for AMQP'),
            [
                new TransportNamesStamp(['app_amqp_transport'])
            ]
        );

        $this->messageBus->dispatch(
            new TestMessage('Message for Kafka'),
            [
                new TransportNamesStamp(['app_kafka_producer'])
            ]
        );

        $this->messageBus->dispatch(
            new TestMessage('Message for default')
        );

        return Command::SUCCESS;
    }
}

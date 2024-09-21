<?php

declare(strict_types=1);

namespace App\Console;

use App\Message\TestMessage;
use App\Messenger\Stamp\KafkaMessageKeyStamp;
use App\Messenger\Stamp\MessageIdStamp;
use App\Messenger\Stamp\SchemaIdStamp;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

#[AsCommand(name: 'app:dispatch-message', description: 'Dispatch message')]
class DispatchMessageCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Dispatch");
//
//        $this->messageBus->dispatch(
//            new TestMessage('Message for Doctrine'),
//            [
//                new TransportNamesStamp(['app_doctrine_transport'])
//            ]
//        );

        $this->messageBus->dispatch(
            new TestMessage('Message for AMQP'),
            [
                new SchemaIdStamp(TestMessage::schemaId()),
                new MessageIdStamp(Uuid::uuid7()->toString()),
                new KafkaMessageKeyStamp(Uuid::uuid7()->toString()),
                new TransportNamesStamp(['app_amqp_transport'])
            ]
        );

//        $this->messageBus->dispatch(
//            new TestMessage('Message for Kafka'),
//            [
//                new TransportNamesStamp(['app_kafka_producer'])
//            ]
//        );
//
//        $this->messageBus->dispatch(
//            new TestMessage('Message for default')
//        );

        return Command::SUCCESS;
    }
}

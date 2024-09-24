<?php

declare(strict_types=1);

namespace App\Console;

use App\Amqp\Amqp;
use App\OutgoingMessageInterface\TestMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:direct-dispatch-amqp-message', description: 'Dispatch message to AMQP')]
class DirectDispatchAmqpMessageCommand extends Command
{
    public function __construct(
        private readonly Amqp $amqp
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Prepare Message");

        $exchange = $this->amqp->createExchange('app_transport_exchange');
        $queue = $this->amqp->createQueue('app_transport_queue');
        $queue->bind('app_transport_exchange', '#');

        $testMessage = new TestMessage('Random content ' . Uuid::uuid4()->toString());
        $message = json_encode($testMessage->serialize());
        $headers = [
            'content_type' => 'application/json',
            'headers' => [
                'x-schema-id' => 'mm.dev.test',
                'x-message-id' => Uuid::uuid7()->toString(),
                'x-some-other' => 'something',
            ],
        ];

        $output->writeln("Publish");
        $exchange->publish($message, 'mm', AMQP_NOPARAM, $headers);
        $this->amqp->waitForConfirm(10);
        $output->writeln("Message confirmed");

        $this->amqp->disconnect();
        return Command::SUCCESS;
    }
}

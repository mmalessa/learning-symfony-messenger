<?php

declare(strict_types=1);

namespace App\Console;

use App\Amqp\Amqp;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:dispatch-amqp-message', description: 'Dispatch message to AMQP')]
class DispatchAmqpMessageCommand extends Command
{
    public function __construct(
        private readonly Amqp $amqp
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Prepare Message");

        $exchange = $this->amqp->createExchange('external_messages');
        $queue = $this->amqp->createQueue('external_messages');
        $queue->bind('external_messages', '#');

        $message = json_encode([
            'correlationId' => 'someProcessId',
            'userId' => Uuid::uuid7()->toString(),
            'userName' => 'Zosia',
            'userAge' => 25,
            'userAddres' => [
                'city' => 'Nowhere',
                'street' => 'Sezamkowa',
                'number' => '1',
            ],
            'timestamp' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ]);
        $headers = [
            'content_type' => 'application/json',
            'headers' => [
                'schema-id' => 'mm.dev.test',
                'message-id' => Uuid::uuid7()->toString(),
                'some-other' => 'something',
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

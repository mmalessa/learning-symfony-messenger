<?php

declare(strict_types=1);

namespace App\Console;

use App\Message\TestMessage;
use App\Messenger\Serializer\IntegrationSerializer;
use App\Messenger\Stamp\SchemaIdStamp;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;

#[AsCommand(name: 'app:play-serializer', description: 'Play serializer')]
class PlaySerializerCommand extends Command
{
    public function __construct(
        private readonly IntegrationSerializer $serializer,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messageObj = new TestMessage('some text');

        $stamps = [
            new SchemaIdStamp(TestMessage::schemaId())
        ];
        $envelope = new Envelope($messageObj, $stamps);

        $serialized = $this->serializer->encode($envelope);

//        print_r($serialized);

        return Command::SUCCESS;
    }
}

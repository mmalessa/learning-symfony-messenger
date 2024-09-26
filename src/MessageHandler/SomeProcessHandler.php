<?php

namespace App\MessageHandler;

use App\Message\IncomingExternal\StartProcess;
use App\Message\OutgoingExternal\DownloadData;
use App\MessengerIntegration\Stamp\MessageIdStamp;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

#[AsMessageHandler]
class SomeProcessHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(StartProcess $message)
    {
        $this->logger->debug(sprintf("Handle StartProcess with content: %s", $message->messageContent));
        $command = new DownloadData('Bring a Christmas tree');
        $stamps = [
            new MessageIdStamp(Uuid::uuid7()->toString()),
            new DelayStamp(1000),
        ];
        $this->messageBus->dispatch($command, $stamps);
//        $event = new DownloadRequested((new \DateTimeImmutable())->format(DATE_ATOM));
//        $this->messageBus->dispatch($event);
    }
}

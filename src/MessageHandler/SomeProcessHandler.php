<?php

namespace App\MessageHandler;

use App\Message\IncomingExternal\StartProcess;
use App\Message\OutgoingExternal\DownloadData;
use App\Message\OutgoingExternal\DownloadRequested;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

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
        $this->messageBus->dispatch($command);
        $event = new DownloadRequested((new \DateTimeImmutable())->format(DATE_ATOM));
        $this->messageBus->dispatch($event);
    }
}

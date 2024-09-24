<?php

namespace App\MessageHandler;

use App\Message\DoSomethingFromExt;
use App\Message\DoSomethingToExt;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class DoSomethingHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(DoSomethingFromExt $message)
    {
        $this->logger->debug(sprintf("Handle DoSomethingFromExt with content: %s", $message->messageContent));
        $command = new DoSomethingToExt('Bring a Christmas tree');
        $this->messageBus->dispatch($command);
    }
}

<?php

namespace App\MessageHandler;

use App\Message\TestMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TestMessageHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(TestMessage $message)
    {
        $this->logger->info(sprintf("Handle TestMessage with content: %s", $message->messageContent));
    }
}

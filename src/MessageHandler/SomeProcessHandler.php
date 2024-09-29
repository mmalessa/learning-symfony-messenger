<?php

namespace App\MessageHandler;

use App\Message\IncomingExternal\StartProcess;
use App\Message\OutgoingExternal\DownloadData;
use App\Message\OutgoingExternal\DownloadRequested;
use App\MessengerIntegration\Stamp\KafkaMessageKeyStamp;
use App\MessengerIntegration\Stamp\MessageIdStamp;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
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

//        $this->messageBus->dispatch(
//            new DownloadData('Bring a Christmas tree'),
//            [
//                new MessageIdStamp(Uuid::uuid7()->toString()),
//            ],
//        );

        $this->messageBus->dispatch(
            new DownloadRequested((new \DateTimeImmutable())->format(DATE_ATOM)),
            [
                new MessageIdStamp(Uuid::uuid7()->toString()),
                new KafkaMessageKeyStamp('some message key'),
            ]
        );
    }
}

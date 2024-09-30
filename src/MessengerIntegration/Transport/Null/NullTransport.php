<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Transport\Null;

use App\MessengerIntegration\Message\UnhandledMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\TransportInterface;

class NullTransport  implements TransportInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ){
    }

    public function send(Envelope $envelope): Envelope
    {
        $message = $envelope->getMessage();
        if (!$message instanceof UnhandledMessage) {
            throw new \Exception('Unhandled message should be instance of UnhandledMessage');
        }

        $messageClassName = get_class($message);
        $this->logger->warning(
            "Null Transport",
            [
                'body' => $message->body,
                'headers' => $message->headers,
                'error_message' => $message->message,
            ]
        );
        return $envelope;
    }

    public function get(): iterable
    {
        return [];
    }

    public function reject(Envelope $envelope): void
    {
    }

    public function ack(Envelope $envelope): void
    {
    }
}

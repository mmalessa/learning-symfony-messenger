<?php

namespace App\MessengerIntegration\Transport\Http;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\SetupableTransportInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;


// TODO
class IntegrationHttpSenderTransport implements TransportInterface, SetupableTransportInterface, MessageCountAwareInterface
{
    public function setup(): void
    {
    }

    public function send(Envelope $envelope): Envelope
    {
        $message = $envelope->getMessage();
        printf("**** HTTP ****\nSending integration HTTP message %s\n**** HTTP ****\n", get_class($message));
//        throw new TransportException('Transport test exception');
        return $envelope;
    }

    public function ack(Envelope $envelope): void
    {
    }

    public function get(): iterable
    {
        return [];
    }

    public function getMessageCount(): int
    {
        return 0;
    }

    public function reject(Envelope $envelope): void
    {
    }
}

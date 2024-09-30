<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Middleware;

use App\MessengerIntegration\Message\UnhandledMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class NullTransportRouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly TransportInterface $nullTransport,
        private readonly LoggerInterface $logger,
    )
    {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        if ($message instanceof UnhandledMessage) {
            return $this->nullTransport->send($envelope);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}

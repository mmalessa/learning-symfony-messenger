<?php

namespace App\MessengerIntegration\Middleware;

use App\Message\OutgoingMessageInterface;
use App\MessengerIntegration\Stamp\OutboxMessageStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

class OutboxRouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TransportInterface $targetTransport,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->logger->debug(sprintf("*** Outbox Router Middleware *** %s", get_class($envelope->getMessage())));
        print_r(array_keys($envelope->all()));


        // SKIP non-incoming
        if (!$this->isOutgoing($envelope)) {
            $this->logger->debug("SKIP Outbox Router Middleware");
            return $stack->next()->handle($envelope, $stack);
        }
        $this->logger->debug("PROCESS Outbox Router Middleware");


        if ($this->hasMessageOutboxStamp($envelope)) {
            $this->logger->debug("Message from Outbox");
            return $stack->next()->handle($envelope, $stack);
        }


        $this->logger->debug("Outgoing Message for Outbox");
        $envelope = $envelope->withoutStampsOfType(TransportNamesStamp::class)->with(new OutboxMessageStamp());
        $this->targetTransport->send($envelope);
        return $envelope;
    }

    private function hasMessageOutboxStamp(Envelope $envelope): bool
    {
        $outboxMessageStamp = $envelope->last(OutboxMessageStamp::class);
        if (null !== $outboxMessageStamp) {
            return true;
        }
        return false;
    }

    private function isOutgoing(Envelope $envelope): bool
    {
        $message = $envelope->getMessage();
        if ($message instanceof OutgoingMessageInterface) {
            return true;
        }
        return false;
    }
}

<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Middleware;

use App\Message\IncomingMessageInterface;
use App\MessengerIntegration\Stamp\InboxMessageStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

class InboxRouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface    $logger,
        private readonly TransportInterface $inboxTransport,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->logger->debug(sprintf("*** Inbox Router Middleware *** %s", get_class($envelope->getMessage())));
        print_r(array_keys($envelope->all()));

        // SKIP
        if (!$this->isIncoming($envelope)) {
            $this->logger->debug("SKIP Inbox Router Middleware");
            return $stack->next()->handle($envelope, $stack);
        }
        $this->logger->debug("PROCESS Inbox Router Middleware");


        if ($this->hasMessageInboxStamp($envelope)) {
            $this->logger->debug("Message from INBOX");
            return $stack->next()->handle($envelope, $stack);
        }


        $this->logger->debug("Incoming Message for INBOX");
        $envelope = $envelope->withoutStampsOfType(TransportNamesStamp::class)->with(new InboxMessageStamp());
        $this->inboxTransport->send($envelope);
        return $envelope;
    }

    private function hasMessageInboxStamp(Envelope $envelope): bool
    {
        $inboxMessageStamp = $envelope->last(InboxMessageStamp::class);
        if (null !== $inboxMessageStamp) {
            return true;
        }
        return false;
    }

    private function isIncoming(Envelope $envelope): bool
    {
        $message = $envelope->getMessage();
        if ($message instanceof IncomingMessageInterface) {
            return true;
        }
        return false;
    }
}

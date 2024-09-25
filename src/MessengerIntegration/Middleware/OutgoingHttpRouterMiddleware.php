<?php

namespace App\MessengerIntegration\Middleware;

use App\Message\OutgoingHttpMessageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class OutgoingHttpRouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface    $logger,
        private readonly TransportInterface $httpTransport,
    ) {
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->logger->debug(sprintf("*** Outgoing HTTP Router Middleware *** %s", get_class($envelope->getMessage())));
        print_r(array_keys($envelope->all()));

        if (!$this->isOutgoingHttpMessage($envelope)) {
            $this->logger->debug("SKIP Outgoing HTTP Router Middleware");
            return $stack->next()->handle($envelope, $stack);
        }
        $this->logger->debug("PROCESS Outgoing HTTP Router Middleware");
        return $this->httpTransport->send($envelope);
    }

    private function isOutgoingHttpMessage(Envelope $envelope): bool
    {
        $message = $envelope->getMessage();
        if ($message instanceof OutgoingHttpMessageInterface) {
            return true;
        }
        return false;
    }
}

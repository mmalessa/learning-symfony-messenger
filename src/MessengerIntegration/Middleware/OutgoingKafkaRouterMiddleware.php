<?php

namespace App\MessengerIntegration\Middleware;

use App\Message\OutgoingKafkaMessageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class OutgoingKafkaRouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface    $logger,
        private readonly TransportInterface $kafkaTransport,
    ) {
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->logger->debug(sprintf("*** Outgoing Kafka Router Middleware *** %s", get_class($envelope->getMessage())));
//        print_r(array_keys($envelope->all()));

        if (!$this->isOutgoingKafkaMessage($envelope)) {
            $this->logger->debug("SKIP Outgoing Kafka Router Middleware");
            return $stack->next()->handle($envelope, $stack);
        }
        $this->logger->debug("PROCESS Outgoing Kafka Router Middleware");
        return $this->kafkaTransport->send($envelope);
    }

    private function isOutgoingKafkaMessage(Envelope $envelope): bool
    {
        $message = $envelope->getMessage();
        if ($message instanceof OutgoingKafkaMessageInterface) {
            return true;
        }
        return false;
    }
}

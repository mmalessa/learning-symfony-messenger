<?php

namespace App\MessengerIntegration\Middleware;

use App\MessengerIntegration\Message\KafkaMessageMapperInterface;
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
        private readonly KafkaMessageMapperInterface $kafkaMessageMapper,
    ) {
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $messageClassName = get_class($message);

        if (!$this->isOutgoingKafkaMessage($envelope)) {
            return $stack->next()->handle($envelope, $stack);
        }
        $this->logger->debug("PROCESS Outgoing Kafka Router Middleware", ['message' => $messageClassName]);
        return $this->kafkaTransport->send($envelope);
    }

    private function isOutgoingKafkaMessage(Envelope $envelope): bool
    {
        $message = $envelope->getMessage();
        $messageClassName = get_class($message);
        return $this->kafkaMessageMapper->match($messageClassName);
    }
}

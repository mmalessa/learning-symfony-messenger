<?php

namespace App\MessengerIntegration\Middleware;

use App\MessengerIntegration\Message\HttpMessageMapperInterface;
use App\MessengerIntegration\Stamp\TargetUrlStamp;
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
        private readonly HttpMessageMapperInterface $httpMessageMapper,
    ) {
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $messageClassName = get_class($envelope->getMessage());
        $targetUrl = $this->httpMessageMapper->getUrlByClassName($messageClassName);

        if (null === $targetUrl) {
            return $stack->next()->handle($envelope, $stack);
        }
        $this->logger->debug("PROCESS Outgoing HTTP Router Middleware", ['message' => $messageClassName]);

        return $this->httpTransport->send($envelope->with(new TargetUrlStamp($targetUrl)));
    }
}

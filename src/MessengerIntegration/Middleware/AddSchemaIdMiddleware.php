<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Middleware;

use App\Message\IntegrationMessageInterface;
use App\MessengerIntegration\Message\SchemaIdMapperInterface;
use App\MessengerIntegration\Stamp\SchemaIdStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class AddSchemaIdMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SchemaIdMapperInterface $schemaIdMapper,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $messageClassName = get_class($message);

        $this->logger->debug("SchemaIdStamp", ['className' => $messageClassName]);
        if (!$message instanceof IntegrationMessageInterface) {
            return $stack->next()->handle($envelope, $stack);
        }

        $this->logger->debug("Add SchemaIdStamp", ['className' => $messageClassName]);

        $schemaId = $this->schemaIdMapper->getSchemaIdByClassName($messageClassName);

        return $stack->next()->handle(
            $envelope->with(new SchemaIdStamp($schemaId)),
            $stack
        );
    }
}

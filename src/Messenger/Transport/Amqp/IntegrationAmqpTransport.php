<?php

declare(strict_types=1);

namespace App\Messenger\Transport\Amqp;

use App\Messenger\Stamp\MessageIdStamp;
use App\Messenger\Stamp\SchemaIdStamp;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpTransport;
use Symfony\Component\Messenger\Envelope;

class IntegrationAmqpTransport extends AmqpTransport
{
    public function send(Envelope $envelope): Envelope
    {
        $message = $envelope->getMessage();
        $stamps = [];

        $schemaIdStamp = $envelope->last(SchemaIdStamp::class);
        if (null !== $schemaIdStamp) {
            $stamps[] = $schemaIdStamp;
        }

        $messageIdStamp = $envelope->last(MessageIdStamp::class);
        if (null !== $messageIdStamp) {
            $stamps[] = $messageIdStamp;
        }

        return parent::send(new Envelope($message, $stamps));
    }
}

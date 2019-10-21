<?php

declare(strict_types=1);

namespace App\EventHandler;

use App\Event\KebabDone;
use App\Event\PizzaDone;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class EventSubscriber implements MessageSubscriberInterface
{
    public static function getHandledMessages(): iterable
    {
        yield KebabDone::class => [
            'method' => 'handleKebabDone',
        ];

        yield PizzaDone::class => [
            'method' => 'handlePizzaDone',
        ];
    }

    public function handleKebabDone(KebabDone $event)
    {
        echo "...Kebab done!\n";
    }

    public function handlePizzaDone(PizzaDone $event)
    {
        echo "...Pizza done!\n";
    }

}

<?php

declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\MakePizza;
use App\Command\MakeKebab;
use App\Event\KebabDone;
use App\Event\PizzaDone;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RestaurantHandler implements MessageSubscriberInterface
{
    private $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public static function getHandledMessages(): iterable
    {
        yield MakePizza::class => [
            'method' => 'handleMakePizzaDough',
            'priority' => -1,

        ];

        yield MakePizza::class => [
            'method' => 'handleBakePizza',
            'priority' => -2,
        ];

        yield MakeKebab::class => [
            'method' => 'handleMakeKebab',
//            'bus' => 'my_bus_name',
//            'from_transport' => 'your_transport_name',
        ];
    }

    public function handleMakePizzaDough(MakePizza $command)
    {
        echo "Make pizza dough.\n";
    }

    public function handleBakePizza(MakePizza $command)
    {
        echo "Bake a pizza.\n";
        $this->eventBus->dispatch(new PizzaDone());
    }

    public function handleMakeKebab(MakeKebab $command)
    {
        echo "Make kebab.\n";
        $this->eventBus->dispatch(new KebabDone());
    }

}

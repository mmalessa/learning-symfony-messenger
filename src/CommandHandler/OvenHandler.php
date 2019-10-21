<?php

declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\MakePizza;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class OvenHandler implements MessageHandlerInterface
{
    private $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    // with priority == 0
    public function __invoke(MakePizza $command)
    {
        echo "Heat the pizza oven.\n";
    }
}

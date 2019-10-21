<?php

declare(strict_types=1);

namespace App\UI\Command;

use App\Command\MakePizza;
use App\Command\MakeKebab;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DoSomething extends Command
{
    protected static $defaultName = 'app:do-something';
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo "SF Command\n";
        $this->commandBus->dispatch(new MakePizza());
        $this->commandBus->dispatch(new MakeKebab());
    }
}

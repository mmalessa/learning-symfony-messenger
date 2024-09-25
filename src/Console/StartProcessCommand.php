<?php

declare(strict_types=1);

namespace App\Console;

use App\Http\FakeController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:start-process', description: 'Hello PhpStorm')]
class StartProcessCommand extends Command
{
    public function __construct(
        private readonly FakeController $controllerEmulator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Emulate request StartProcess via HTTP");
        $this->controllerEmulator->requestStartProcess();
        return Command::SUCCESS;
    }
}

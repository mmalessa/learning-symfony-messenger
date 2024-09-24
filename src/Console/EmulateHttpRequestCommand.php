<?php

declare(strict_types=1);

namespace App\Console;

use App\Http\ControllerEmulator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:emulate-http-request', description: 'Hello PhpStorm')]
class EmulateHttpRequestCommand extends Command
{
    public function __construct(
        private readonly ControllerEmulator $controllerEmulator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Emulate request DoSomethingFromExt via HTTP");
        $this->controllerEmulator->requestTestMessage();
        return Command::SUCCESS;
    }
}

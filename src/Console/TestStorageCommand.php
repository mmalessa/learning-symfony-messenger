<?php

declare(strict_types=1);

namespace App\Console;

use App\MessengerIntegration\Message\SchemaIdMapperInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test-storage', description: 'Hello PhpStorm')]
class TestStorageCommand extends Command
{
    public function __construct(
        private readonly SchemaIdMapperInterface $schemaIdMapper,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = $this->schemaIdMapper->getDebug();
        print_r($content);
        return Command::SUCCESS;
    }
}

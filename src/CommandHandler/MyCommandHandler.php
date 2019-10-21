<?php

declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\MyCommand;

class MyCommandHandler
{
    public function __invoke(MyCommand $command)
    {
        echo "Inside Invoke\n";
    }
}

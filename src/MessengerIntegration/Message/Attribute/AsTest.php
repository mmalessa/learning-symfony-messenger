<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Message\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsTest
{
    public function __construct(
    ) {
    }
}

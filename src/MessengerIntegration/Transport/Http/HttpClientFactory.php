<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Transport\Http;

use GuzzleHttp\Client;

class HttpClientFactory
{
    public function create(array $config): Client
    {
        return new Client($config);
    }
}

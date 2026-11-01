<?php
declare(strict_types=1);

namespace App\Http;

use Cake\Http\Client;
use Cake\Http\Client\Request;

class AppClient extends Client
{
    protected function createRequest(string $method, string $url, array|string|null $data, array $options): Request
    {
        return parent::createRequest($method, $url, $data, $options);
    }

    protected function createAuth(array $auth): \Cake\Http\Client\Auth\AuthenticationInterface
    {
        return parent::createAuth($auth);
    }
}

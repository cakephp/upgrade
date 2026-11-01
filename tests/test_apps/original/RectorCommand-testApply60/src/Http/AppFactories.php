<?php
declare(strict_types=1);

namespace App\Http;

use Cake\Http\RequestFactory;
use Cake\Http\ResponseFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AppRequestFactory extends RequestFactory
{
    public function createRequest(string $method, $uri): RequestInterface
    {
        return parent::createRequest($method, $uri);
    }
}

class AppResponseFactory extends ResponseFactory
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return parent::createResponse($code, $reasonPhrase);
    }
}

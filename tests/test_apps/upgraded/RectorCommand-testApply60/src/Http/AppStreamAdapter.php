<?php
declare(strict_types=1);

namespace App\Http;

use Cake\Http\Client\Adapter\Stream;
use Cake\Http\Client\Response;

class AppStreamAdapter extends Stream
{
    public function createResponses(array $headers, string|\Psr\Http\Message\StreamInterface $content): array
    {
        return parent::createResponses($headers, $content);
    }

    protected function buildResponse(array $headers, string|\Psr\Http\Message\StreamInterface $body): Response
    {
        return parent::buildResponse($headers, $body);
    }
}

<?php
declare(strict_types=1);

namespace App\Http;

use Cake\Http\Client\Adapter\Stream;
use Cake\Http\Client\Response;

class AppStreamAdapter extends Stream
{
    public function createResponses(array $headers, string $content): array
    {
        return parent::createResponses($headers, $content);
    }

    protected function buildResponse(array $headers, string $body): Response
    {
        return parent::buildResponse($headers, $body);
    }
}

<?php
declare(strict_types=1);

namespace App\Http;

use Cake\Http\ResponseEmitter;
use Psr\Http\Message\ResponseInterface;

class AppEmitter extends ResponseEmitter
{
    public function emit(ResponseInterface $response): void
    {
        parent::emit($response);
    }
}

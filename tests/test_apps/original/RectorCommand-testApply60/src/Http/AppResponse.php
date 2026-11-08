<?php
declare(strict_types=1);

namespace App\Http;

use Cake\Http\Response;
use Psr\Http\Message\MessageInterface;

class AppResponse extends Response
{
    public function withHeader(string $name, $value): MessageInterface
    {
        return parent::withHeader($name, $value);
    }
}

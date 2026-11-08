<?php
declare(strict_types=1);

namespace App\Http;

use Laminas\Diactoros\MessageTrait;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Stream;
use Laminas\Diactoros\UploadedFile;
use Laminas\Diactoros\Uri;

class DiactorosUsage
{
    use \Cake\Http\MessageTrait;

    public function build(): array
    {
        $uri = new \Cake\Http\Uri('https://example.com');
        $stream = new \Cake\Http\Stream('php://memory', 'wb+');
        $file = new \Cake\Http\UploadedFile($stream, 0, UPLOAD_ERR_OK, 'file.txt', 'text/plain');
        $redirect = new \Cake\Http\Response\RedirectResponse($uri);

        return [$uri, $stream, $file, $redirect];
    }
}

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
    use MessageTrait;

    public function build(): array
    {
        $uri = new Uri('https://example.com');
        $stream = new Stream('php://memory', 'wb+');
        $file = new UploadedFile($stream, 0, UPLOAD_ERR_OK, 'file.txt', 'text/plain');
        $redirect = new RedirectResponse($uri);

        return [$uri, $stream, $file, $redirect];
    }
}

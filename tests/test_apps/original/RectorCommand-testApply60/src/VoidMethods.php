<?php
declare(strict_types=1);

namespace App;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;

class VoidMethods
{
    public function test(ServerRequest $request): void
    {
        // Instance method - assignment should be removed
        $result = $request->allowMethod(['POST']);

        // Static method - assignment should be removed
        $result = Configure::load('app');

        // Without assignment - should stay as is
        $request->allowMethod(['GET', 'POST']);
        Configure::load('other');
    }
}

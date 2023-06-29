<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SomeTest extends TestCase
{
    use IntegrationTestTrait;

    public function testSomething()
    {
        $this->useCommandRunner();
        $this->useHttpServer();
        $this->get('/');
    }
}

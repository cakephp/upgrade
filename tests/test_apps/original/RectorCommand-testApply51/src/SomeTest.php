<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\ConnectionHelper;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SomeTest extends TestCase
{
    use IntegrationTestTrait;

    public function testSomething()
    {
        $config = [
            '_cake_core_' => [
                'className' => 'FileEngine',
                'prefix' => 'myapp_cake_core_',
                'path' => 'persistent',
                'serialize' => true,
                'duration' => '+1 years',
            ],
        ];
    }

    public function testConnectionHelper()
    {
        $connectionHelper = new ConnectionHelper();
        $connection = ConnectionManager::get('test');
        $connectionHelper->runWithoutConstraints($connection, function ($connection) {
            $connection->execute('SELECT * FROM table');
        });
        $connectionHelper->dropTables('test', ['table']);
        $connectionHelper->enableQueryLogging(['test']);
        $connectionHelper->truncateTables('test', ['table']);
        $connectionHelper->addTestAliases();
    }
}

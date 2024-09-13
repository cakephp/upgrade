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
            '_cake_translations_' => [
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
        \Cake\TestSuite\ConnectionHelper::runWithoutConstraints($connection, function ($connection) {
            $connection->execute('SELECT * FROM table');
        });
        \Cake\TestSuite\ConnectionHelper::dropTables('test', ['table']);
        \Cake\TestSuite\ConnectionHelper::enableQueryLogging(['test']);
        \Cake\TestSuite\ConnectionHelper::truncateTables('test', ['table']);
        \Cake\TestSuite\ConnectionHelper::addTestAliases();
    }
}

<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\EventManagerOnRector\Fixture;

use Cake\Event\EventManager;

class SkipAlreadyTransformed
{
    public function run()
    {
        $eventManager = EventManager::instance();

        // Should NOT be transformed - already in new format with closure as 2nd arg
        $eventManager->on('Model.beforeSave', function ($event) {
            return true;
        }, ['priority' => 90]);

        // Should NOT be transformed - already in new format with arrow function as 2nd arg
        $eventManager->on('Controller.initialize', fn($event) => true, ['priority' => 10]);

        // Should NOT be transformed - already in new format with variable callable
        $eventManager->on('Model.beforeFind', $callable, ['priority' => 100]);
    }
}

?>

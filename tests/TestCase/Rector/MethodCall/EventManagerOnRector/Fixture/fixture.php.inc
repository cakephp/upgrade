<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\EventManagerOnRector\Fixture;

use Cake\Event\EventManager;

class Fixture
{
    public function run()
    {
        $eventManager = EventManager::instance();
        
        // Should be transformed - 3 arguments with array literal
        $eventManager->on('Model.beforeSave', ['priority' => 90], $callable);
        
        // Should be transformed - 3 arguments with different options
        $eventManager->on('Controller.initialize', ['priority' => 10], function () {
            return true;
        });
        
        // Should NOT be transformed - 2 arguments
        $eventManager->on('Model.afterSave', $callable);
        
        // Should NOT be transformed - 1 argument
        $eventManager->on('Model.afterDelete');
        
        // Should be transformed - 3 arguments with variable
        $options = ['priority' => 100];
        $eventManager->on('Model.beforeFind', $options, $handler);

        // Should be transformed - 3 arguments with arrow function as 3rd arg
        $eventManager->on('Model.beforeValidate', ['priority' => 50], fn($event) => true);
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\EventManagerOnRector\Fixture;

use Cake\Event\EventManager;

class Fixture
{
    public function run()
    {
        $eventManager = EventManager::instance();
        
        // Should be transformed - 3 arguments with array literal
        $eventManager->on('Model.beforeSave', $callable, ['priority' => 90]);
        
        // Should be transformed - 3 arguments with different options
        $eventManager->on('Controller.initialize', function () {
            return true;
        }, ['priority' => 10]);
        
        // Should NOT be transformed - 2 arguments
        $eventManager->on('Model.afterSave', $callable);
        
        // Should NOT be transformed - 1 argument
        $eventManager->on('Model.afterDelete');
        
        // Should be transformed - 3 arguments with variable
        $options = ['priority' => 100];
        $eventManager->on('Model.beforeFind', $handler, $options);

        // Should be transformed - 3 arguments with arrow function as 3rd arg
        $eventManager->on('Model.beforeValidate', fn($event) => true, ['priority' => 50]);
    }
}

?>

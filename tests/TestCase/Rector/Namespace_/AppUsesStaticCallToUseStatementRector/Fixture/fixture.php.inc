<?php

namespace Cake\Upgrade\Test\TestCase\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\Fixture;

\App::uses('NotificationListener', 'Event');

class SomeClass
{
    public function run()
    {
        $values = new NotificationListener();
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\Fixture;

use Event\NotificationListener;

class SomeClass
{
    public function run()
    {
        $values = new NotificationListener();
    }
}

?>

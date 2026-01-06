<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\StaticConnectionHelperRector\Fixture;

class Fixture
{
    public function run()
    {
        $connectionHelper = new \Cake\TestSuite\ConnectionHelper();
        $connection = $connectionHelper->getConnection('default');

        return $connection;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\StaticConnectionHelperRector\Fixture;

class Fixture
{
    public function run()
    {
        $connection = \Cake\TestSuite\ConnectionHelper::getConnection('default');

        return $connection;
    }
}

?>

<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ModalToGetSetRector\Fixture;

class Fixture
{
    function run()
    {
        $this->regularMethod('call');
        $this->getTableLocator()->otherMethod();
        $this->Users = $this->getTableLocator()->get('Users');
        $articles = $this->getTableLocator()->get('Articles', ['table' => 'alt_articles']);
        $comments = $this->getTableLocator()
            ->get('Comments');
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ModalToGetSetRector\Fixture;

class Fixture
{
    function run()
    {
        $this->regularMethod('call');
        $this->getTableLocator()->otherMethod();
        $this->Users = $this->fetchTable('Users');
        $articles = $this->fetchTable('Articles', ['table' => 'alt_articles']);
        $comments = $this->fetchTable('Comments');
    }
}

?>

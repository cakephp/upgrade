<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Command\Helper\TableHelper;
use Cake\ORM\Locator\LocatorAwareTrait;

class SomeTest
{
    use LocatorAwareTrait;

    private \Cake\Console\Helper\TableHelper $Table;

    public function testRenames(): void
    {
        $table = $this->fetchTable('Articles');
        $results = $table->unhydratedFind()->where(['published' => true]);
    }
}

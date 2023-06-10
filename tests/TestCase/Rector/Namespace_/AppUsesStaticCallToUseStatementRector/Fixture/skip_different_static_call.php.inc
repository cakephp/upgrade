<?php

namespace Cake\Upgrade\Test\TestCase\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\Fixture;

\DIFFERENT_APP::uses('NotificationListener', 'Event');

class SkipDifferentStaticCall
{
    public function run()
    {
        $values = new NotificationListener();
    }
}
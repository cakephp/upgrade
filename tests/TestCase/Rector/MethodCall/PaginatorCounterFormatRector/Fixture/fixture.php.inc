<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\PaginatorCounterFormatRector\Fixture;

use Cake\View\Helper\PaginatorHelper;

function displayPagination(PaginatorHelper $paginator)
{
    // This should be converted
    echo $paginator->counter(['format' => __('Page {{page}} of {{pages}}')]);

    // This should NOT be converted (already using direct format)
    echo $paginator->counter(__('Showing {{current}} of {{count}}'));

    // This should NOT be converted (has more than one array item)
    echo $paginator->counter(['format' => __('Page {{page}}'), 'model' => 'Articles']);
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\PaginatorCounterFormatRector\Fixture;

use Cake\View\Helper\PaginatorHelper;

function displayPagination(PaginatorHelper $paginator)
{
    // This should be converted
    echo $paginator->counter(__('Page {{page}} of {{pages}}'));

    // This should NOT be converted (already using direct format)
    echo $paginator->counter(__('Showing {{current}} of {{count}}'));

    // This should NOT be converted (has more than one array item)
    echo $paginator->counter(['format' => __('Page {{page}}'), 'model' => 'Articles']);
}

?>

<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\BreadcrumbsHelperAddManyRector\Fixture;

use Cake\View\Helper\BreadcrumbsHelper;

class SomeView
{
    private BreadcrumbsHelper $Breadcrumbs;

    public function someMethod()
    {
        // Should transform: add with array of crumbs
        $this->Breadcrumbs->add([
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Articles', 'url' => '/articles'],
        ]);

        // Should transform: prepend with array of crumbs
        $this->Breadcrumbs->prepend([
            ['title' => 'Dashboard'],
        ]);
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\BreadcrumbsHelperAddManyRector\Fixture;

use Cake\View\Helper\BreadcrumbsHelper;

class SomeView
{
    private BreadcrumbsHelper $Breadcrumbs;

    public function someMethod()
    {
        // Should transform: add with array of crumbs
        $this->Breadcrumbs->addMany([
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Articles', 'url' => '/articles'],
        ]);

        // Should transform: prepend with array of crumbs
        $this->Breadcrumbs->prependMany([
            ['title' => 'Dashboard'],
        ]);
    }
}

?>

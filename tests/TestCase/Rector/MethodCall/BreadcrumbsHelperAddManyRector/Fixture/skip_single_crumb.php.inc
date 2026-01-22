<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\BreadcrumbsHelperAddManyRector\Fixture;

use Cake\View\Helper\BreadcrumbsHelper;

class SomeView
{
    private BreadcrumbsHelper $Breadcrumbs;

    public function someMethod()
    {
        // Should NOT transform: single crumb with title/url
        $this->Breadcrumbs->add('Home', '/');

        // Should NOT transform: single crumb as array with title key
        $this->Breadcrumbs->add(['title' => 'Home', 'url' => '/']);

        // Should NOT transform: prepend single crumb
        $this->Breadcrumbs->prepend('Dashboard');
    }
}

?>

<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\View\Helper\BreadcrumbsHelper;

class BreadcrumbsExample
{
    private BreadcrumbsHelper $Breadcrumbs;

    public function testBreadcrumbs(): void
    {
        // Single crumb with title key should be changed to content
        $this->Breadcrumbs->add(['title' => 'Home', 'url' => '/']);

        // Multiple crumbs with title keys should be changed to content
        $this->Breadcrumbs->addMany([
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Articles', 'url' => '/articles'],
        ]);

        // Prepend with title key should be changed to content
        $this->Breadcrumbs->prepend(['title' => 'Dashboard', 'url' => '/dashboard']);

        // PrependMany with title keys should be changed to content
        $this->Breadcrumbs->prependMany([
            ['title' => 'Admin', 'url' => '/admin'],
        ]);

        // String argument should stay as is
        $this->Breadcrumbs->add('Contact', '/contact');

        // Named parameter title should be changed to content
        $this->Breadcrumbs->add(title: 'About', url: '/about');

        // insertBefore with named parameters
        $this->Breadcrumbs->insertBefore(matchingTitle: 'Home', title: 'Start', url: '/start');

        // insertAfter with named parameters
        $this->Breadcrumbs->insertAfter(matchingTitle: 'Home', title: 'Next', url: '/next');

        // insertAt with named parameter
        $this->Breadcrumbs->insertAt(0, title: 'First', url: '/first');
    }
}

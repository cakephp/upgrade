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
        $this->Breadcrumbs->add(['content' => 'Home', 'url' => '/']);

        // Multiple crumbs with title keys should be changed to content
        $this->Breadcrumbs->addMany([
            ['content' => 'Home', 'url' => '/'],
            ['content' => 'Articles', 'url' => '/articles'],
        ]);

        // Prepend with title key should be changed to content
        $this->Breadcrumbs->prepend(['content' => 'Dashboard', 'url' => '/dashboard']);

        // PrependMany with title keys should be changed to content
        $this->Breadcrumbs->prependMany([
            ['content' => 'Admin', 'url' => '/admin'],
        ]);

        // String argument should stay as is
        $this->Breadcrumbs->add('Contact', '/contact');

        // Named parameter title should be changed to content
        $this->Breadcrumbs->add(content: 'About', url: '/about');

        // insertBefore with named parameters
        $this->Breadcrumbs->insertBefore(matchingContent: 'Home', content: 'Start', url: '/start');

        // insertAfter with named parameters
        $this->Breadcrumbs->insertAfter(matchingContent: 'Home', content: 'Next', url: '/next');

        // insertAt with named parameter
        $this->Breadcrumbs->insertAt(0, content: 'First', url: '/first');
    }
}

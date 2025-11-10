<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryParamAccessRector\Fixture;

use Cake\Http\ServerRequest;

class MyController
{
    public function index()
    {
        $request = new ServerRequest();

        // Should transform: getParam('?')
        $queryParams = $request->getParam('?');

        // Should also work with method chaining
        $params = $this->getRequest()->getParam('?');

        return $queryParams;
    }

    protected function getRequest(): ServerRequest
    {
        return new ServerRequest();
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryParamAccessRector\Fixture;

use Cake\Http\ServerRequest;

class MyController
{
    public function index()
    {
        $request = new ServerRequest();

        // Should transform: getParam('?')
        $queryParams = $request->getQueryParams();

        // Should also work with method chaining
        $params = $this->getRequest()->getQueryParams();

        return $queryParams;
    }

    protected function getRequest(): ServerRequest
    {
        return new ServerRequest();
    }
}

?>

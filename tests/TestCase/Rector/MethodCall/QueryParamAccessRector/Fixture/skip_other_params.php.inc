<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryParamAccessRector\Fixture;

use Cake\Http\ServerRequest;

class MyController
{
    public function index()
    {
        $request = new ServerRequest();

        // Should NOT transform: getParam with different argument
        $id = $request->getParam('id');
        $action = $request->getParam('action');
        $paging = $request->getParam('paging');

        // Should NOT transform: non-Request object
        $someObject->getParam('?');

        return compact('id', 'action', 'paging');
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

        // Should NOT transform: getParam with different argument
        $id = $request->getParam('id');
        $action = $request->getParam('action');
        $paging = $request->getParam('paging');

        // Should NOT transform: non-Request object
        $someObject->getParam('?');

        return compact('id', 'action', 'paging');
    }
}

?>

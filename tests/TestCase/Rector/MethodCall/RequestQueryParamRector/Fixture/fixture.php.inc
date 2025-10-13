<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RequestQueryParamRector\Fixture;

use Cake\Http\ServerRequest;

function requestQueryParam()
{
    $request = new ServerRequest();
    $queryParams = $request->getParam('?');

    // Should not change - different parameter
    $otherParam = $request->getParam('foo');
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RequestQueryParamRector\Fixture;

use Cake\Http\ServerRequest;

function requestQueryParam()
{
    $request = new ServerRequest();
    $queryParams = $request->getQueryParams();

    // Should not change - different parameter
    $otherParam = $request->getParam('foo');
}

?>

<?php

declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\Fixture;

\App::uses('Component', 'Controller');

class CakeControllerWithStrictTypes
{
}

?>
-----
<?php

declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\Fixture;

use Cake\Controller\Component;

class CakeControllerWithStrictTypes
{
}

?>

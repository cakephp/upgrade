<?php

namespace Cake\Upgrade\Test\TestCase\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\Fixture;

class InsideIf
{
    public function test()
    {
        if (rand(0, 1)) {
            \App::uses('HtmlDomLib', 'Foo.Lib');
            $HtmlDom = new HtmlDomLib();
            \App::uses('HtmlDomLibExt', 'Foo.Lib');
            $HtmlDom = new HtmlDomLibExt();
        }
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\Fixture;

use Foo\Lib\HtmlDomLib;
use Foo\Lib\HtmlDomLibExt;
class InsideIf
{
    public function test()
    {
        if (rand(0, 1)) {
            $HtmlDom = new HtmlDomLib();
            $HtmlDom = new HtmlDomLibExt();
        }
    }
}

?>

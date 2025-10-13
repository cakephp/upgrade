<?php

namespace Cake\Upgrade\Test\TestCase\Rector\String_\RenameFormHelperTemplateKeyRector\Fixture;

class SomeController
{
    public function index()
    {
        // In FormHelper setTemplates
        $this->Form->setTemplates([
            'multicheckboxTitle' => '<legend>{{text}}</legend>',
            'inputContainer' => '<div class="input">{{content}}</div>',
        ]);

        // In config array
        $config = [
            'templates' => [
                'multicheckboxTitle' => '<div>{{text}}</div>',
            ],
        ];

        // Should not change - as a value, not a key
        $someVar = 'multicheckboxTitle';
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\String_\RenameFormHelperTemplateKeyRector\Fixture;

class SomeController
{
    public function index()
    {
        // In FormHelper setTemplates
        $this->Form->setTemplates([
            'multicheckboxLabel' => '<legend>{{text}}</legend>',
            'inputContainer' => '<div class="input">{{content}}</div>',
        ]);

        // In config array
        $config = [
            'templates' => [
                'multicheckboxLabel' => '<div>{{text}}</div>',
            ],
        ];

        // Should not change - as a value, not a key
        $someVar = 'multicheckboxTitle';
    }
}

?>

<?php

namespace Cake\Upgrade\Test\TestCase\Rector\ClassMethod\FormExecuteToProcessRector\Fixture;

use Cake\Form\Form;

class ContactForm extends Form
{
    protected function _execute(array $data, $form): bool
    {
        // Send email or save data
        return true;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\ClassMethod\FormExecuteToProcessRector\Fixture;

use Cake\Form\Form;

class ContactForm extends Form
{
    protected function process(array $data, $form): bool
    {
        // Send email or save data
        return true;
    }
}

?>

<?php

namespace Cake\Upgrade\Test\TestCase\Rector\ClassMethod\FormExecuteToProcessRector\Fixture;

use Cake\Form\Form;

class ContactForm extends Form
{
    protected function _execute(array $data, $form): bool
    {
        // Old method
        return true;
    }

    protected function process(array $data, $form): bool
    {
        // New method already exists
        return true;
    }
}

<?php

namespace Cake\Upgrade\Test\TestCase\Rector\ClassMethod\FormBuildValidatorRector\Fixture;

use Cake\Form\Form;
use Cake\Event\Event;
use Cake\Validation\Validator;

class ContactForm extends Form
{
    public function buildValidator(Event $event, Validator $validator, $name)
    {
        $validator
            ->add('name', 'length', [
                'rule' => ['minLength', 10],
                'message' => 'Name is required',
            ])
            ->add('email', 'format', [
                'rule' => 'email',
                'message' => 'A valid email address is required',
            ]);

        return $validator;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\ClassMethod\FormBuildValidatorRector\Fixture;

use Cake\Form\Form;
use Cake\Event\Event;
use Cake\Validation\Validator;

class ContactForm extends Form
{
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->add('name', 'length', [
                'rule' => ['minLength', 10],
                'message' => 'Name is required',
            ])
            ->add('email', 'format', [
                'rule' => 'email',
                'message' => 'A valid email address is required',
            ]);

        return $validator;
    }
}

?>

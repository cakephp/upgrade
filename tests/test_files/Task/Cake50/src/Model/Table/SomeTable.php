<?php

namespace SomePlugin\Model\Table;

use Cake\Validation\Validator;

class SomeTable {

	public function validationDefault(Validator $validator): Validator {
		$validator->allowEmpty('x', 'y');
	}

}

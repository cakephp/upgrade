<?php

namespace SomePlugin\Model\Table;

use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\Validation\Validator;

class SomeTable {

	public function validationDefault(Validator $validator): Validator {
		$validator->allowEmpty('x', 'y');
	}

	/**
	 * @param \Cake\Event\EventInterface $event
	 * @param \Cake\ORM\Query $query
	 * @param \ArrayObject $options
	 * @param bool $primary
	 * @return \Cake\ORM\Query
	 */
	public function beforeFind(EventInterface $event, Query $query, ArrayObject $options, bool $primary) {
		return $query;
	}

}

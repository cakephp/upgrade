<?php

namespace SomePlugin\Model\Behavior;
use Cake\Database\Type;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use PHPStan\Type\ArrayType;

class SomeBehavior extends Behavior
{
	/**
	 * @var array<string, mixed>
	 */
	protected $_defaultConfig = [
	];

	public function mapExample(): void {
		Type::map('array', ArrayType::class);
	}

	/**
	 * @param \Cake\ORM\Query $query
	 * @param array $options
	 *
	 * @return \Cake\ORM\Query
	 */
	public function findExposedList(Query $query, array $options) {
		return $query;
	}
}

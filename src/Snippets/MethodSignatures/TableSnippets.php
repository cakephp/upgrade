<?php

namespace Cake\Upgrade\Snippets\MethodSignatures;

class TableSnippets {

	/**
	 * @var array<string>
	 */
	protected $callbacks = [
		'beforeFind',
		'buildValidator',
		'buildRules',
		'beforeMarshal',
		'afterMarshal',
		'beforeRules',
		'afterRules',
		'beforeSave',
		'afterSave',
		'afterSaveCommit',
		'beforeDelete',
		'afterDelete',
		'afterDeleteCommit',
	];

	/**
	 * @var array<string>
	 */
	protected $returnTypes = [
		'',
	];

	/**
	 * @return array
	 */
	public function snippets(): array {
		$list = [
			[
				'initialize(array $config)',
				'#public function initialize\(array \$config\)(?!:)#i',
				'public function initialize(array $config): void',
			], [
				'_initializeSchema()',
				'#protected function _initializeSchema\((.+) \$schema\): TableSchema(?!Interface)#i',
				'protected function _initializeSchema(\1 $schema): \Cake\Database\Schema\TableSchemaInterface',
			], [
				'_initializeSchema() signature',
				'#protected function _initializeSchema\(TableSchema \$schema\)#i',
				'protected function _initializeSchema(\Cake\Database\Schema\TableSchemaInterface $schema)',
			], [
				'_initializeSchema() return type',
				'#protected function _initializeSchema\((.+)\)(?!:)#i',
				'protected function _initializeSchema(\1): \Cake\Database\Schema\TableSchemaInterface',
			], [
				'buildRules(RulesChecker $rules)',
				'#public function buildRules\(RulesChecker \$rules\)(?!:)#i',
				'public function buildRules(RulesChecker $rules): RulesChecker',
			],
			[
				'validationDefault(Validator $validator)',
				'#public function validationDefault\(Validator \$validator\)(?!:)#i',
				'public function validationDefault(Validator $validator): Validator',
			],
			[
				'->allowEmptyString() API change',
				'#-\>allowEmptyString\((\'\w+\'), (true|false|\'create\'|\'update\')\)#',
				'->allowEmptyString(\1, null, \2)',
			],
		];

		foreach ($this->callbacks as $callback) {
			$list[] = [
				$callback . ' callback',
				'#\bfunction ' . $callback . '\(Event \$event\b#i',
				'function ' . $callback . '(\Cake\Event\EventInterface $event',
			];
		}

		return $list;
	}

}

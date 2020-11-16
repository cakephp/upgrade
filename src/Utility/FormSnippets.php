<?php

namespace Cake\Upgrade\Utility;

class FormSnippets {

	/**
	 * @var string[]
	 */
	protected $callbacks = [
		'beforeFilter',
		'beforeRender',
		'beforeRedirect',
		'afterFilter',
	];

	/**
	 * @return array
	 */
	public function snippets(): array {
		$list = [
			[
				'_buildSchema() method',
				'#\bprotected function _buildSchema\(Schema \$schema\)(?!:)#i',
				'protected function _buildSchema(Schema $schema): Schema',
			], [
				'_execute() method',
				'#\bprotected function _execute\(array \$data\)(?!:)#i',
				'protected function _execute(array $data): bool',
			], [
				'validationDefault() method',
				'#\bpublic function validationDefault\(Validator \$validator\)(?!:)#i',
				'public function validationDefault(Validator $validator): Validator',
			], [
				'buildValidator() method',
				'#\bpublic function buildValidator\(Validator \$validator, string \$name\)(?!:)#i',
				'public function validationDefault(Validator $validator, string $name): void',
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

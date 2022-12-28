<?php

namespace Cake\Upgrade\Snippets\MethodSignatures;

class ComponentSnippets {

	/**
	 * @var array<string>
	 */
	protected $callbacks = [
		'beforeFilter',
		'startup',
		'beforeRender',
		'beforeRedirect',
		'shutdown',
	];

	/**
	 * @return array
	 */
	public function snippets(): array {
		$list = [
			[
				'initialize() return type',
				'#\bpublic function initialize\(array \$config\)(?!:)#i',
				'public function initialize(array $config): void',
			],
			[
				'->request-> to ->getRequest()->',
				'#-\>request-\>#',
				'->getRequest()->',
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

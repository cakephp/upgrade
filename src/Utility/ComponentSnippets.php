<?php

namespace Cake\Upgrade\Utility;

class ComponentSnippets {

	/**
	 * @var string[]
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
	public function events(): array {
		$list = [
			[
				'initialize() return type',
				'#\bpublic function initialize\(\)(?!:)#i',
				'public function initialize(): void',
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

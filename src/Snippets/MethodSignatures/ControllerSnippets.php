<?php

namespace Cake\Upgrade\Snippets\MethodSignatures;

class ControllerSnippets {

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
		return array_merge(
			$this->events(),
			$this->plugins()
		);
	}

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
			[
				'$modelClass visibility',
				'#\bpublic \$modelClass =#i',
				'protected $modelClass =',
			],
			[
				'$modelClass visibility',
				'#\bprotected \$modelClass = false;#i',
				'protected $modelClass = \'\';',
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

	/**
	 * @return array
	 */
	public function plugins(): array {
		$list = [
			[
				'Search.Search component',
				'#\'Search\.Prg\'#',
				'\'Search.Search\'',
			],
		];

		return $list;
	}

}

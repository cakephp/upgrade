<?php

namespace Cake\Upgrade\Utility;

class ViewSnippets {

	/**
	 * @return array
	 */
	public function snippets(): array {
		$list = [
			[
				'render() signature',
				'#public function render\(\$view = null, \$layout = null\)#i',
				'public function render(?string $view = null, $layout = null)',
			],
			[
				'render() return type',
				'#public function render\((.*)\$view = null, \$layout = null\)(?!:)#i',
				'public function render(\1$view = null, $layout = null): string',
			],
			[
				'dispatchEvent() signature',
				'#public function dispatchEvent\(\$name, \$data = null, \$subject = null\)#i',
				'public function dispatchEvent(string $name, ?array $data = null, ?object $subject = null)',
			],
			[
				'dispatchEvent() return type',
				'#public function dispatchEvent\(([^\]]+)\)(?!:)#i',
				'public function dispatchEvent(\1): \Cake\Event\EventInterface',
			],
		];

		return $list;
	}

}

<?php

namespace Cake\Upgrade\Utility;

class ShellTaskSnippets extends AbstractSnippets {

	/**
	 * @param string $path
	 *
	 * @return array
	 */
	public function snippets(string $path): array {
		$list = [
			'$this->out()' => [
				'#\$this-\>out\(\)#',
				'$this->out(\'\')',
			],
		];

		if (strpos($path, 'src' . DS . 'Shell' . DS . 'Task' . DS . 'Queue') !== false) {
			$list = [
				'queue task signature' => [
					'#public function run\(array \$data, \$(\w+)\)#i',
					'public function run(array $data, int $\1)',
				],
				'queue task return type' => [
					'#public function run\(array \$data, (.+)\)(?!:)#i',
					'public function run(array $data, \1): void',
				],
			];
		}

		return $list;
	}

}

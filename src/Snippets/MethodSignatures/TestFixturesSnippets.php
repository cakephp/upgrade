<?php

namespace Cake\Upgrade\Snippets\MethodSignatures;

class TestFixturesSnippets {

	/**
	 * @return array
	 */
	public function snippets(): array {
		$list = [
			[
				'public function init()',
				'#public function init\(\)(?!:)#',
				'public function init(): void',
			],
		];

		return $list;
	}

}

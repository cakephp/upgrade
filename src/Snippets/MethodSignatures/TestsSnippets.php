<?php

namespace Cake\Upgrade\Snippets\MethodSignatures;

class TestsSnippets {

	/**
	 * @var array<string>
	 */
	protected $callbacks = [
		'setUp',
		'tearDown',
	];

	/**
	 * @return array
	 */
	public function snippets(): array {
		$list = [
			[
				'$fixtures visibility',
				'#public \$fixtures =#i',
				'protected $fixtures =',
			], [
				'assertStringContainsString()',
				'#\$this-\>assertContains\(#i',
				'$this->assertStringContainsString(',
			], [
				'assertStringNotContainsString()',
				'#\$this-\>assertNotContains\(#i',
				'$this->assertStringNotContainsString(',
			],
		];

		foreach ($this->callbacks as $callback) {
			$list[] = [
				$callback . ' callback',
				'#public function ' . $callback . '\(\)(?!:)#i',
				'public function ' . $callback . '(): void',
			];
		}

		return $list;
	}

}

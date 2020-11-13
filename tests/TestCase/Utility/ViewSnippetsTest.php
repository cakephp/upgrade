<?php

namespace Cake\Upgrade\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Utility\ExecTrait;
use Cake\Upgrade\Utility\ViewSnippets;

class ViewSnippetsTest extends TestCase {

	use ChangeTrait;
	use ExecTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new ViewSnippets())->snippets();

		$string = 'public function dispatchEvent($name, $data = null, $subject = null)';
		$result = $this->exec($string, $replacements);
		$expected = 'public function dispatchEvent(string $name, ?array $data = null, ?object $subject = null): \Cake\Event\EventInterface';
		$this->assertSame($expected, $result);

		$string = 'public function render($view = null, $layout = null)';
		$result = $this->exec($string, $replacements);
		$expected = 'public function render(?string $view = null, $layout = null): string';
		$this->assertSame($expected, $result);
	}

}

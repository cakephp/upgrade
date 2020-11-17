<?php

namespace Cake\Upgrade\Test\TestCase\Snippets\MethodSignatures;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Snippets\MethodSignatures\ControllerSnippets;

class ControllerSnippetsTest extends TestCase {

	use ChangeTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new ControllerSnippets())->events();

		$string = 'public function beforeFilter(Event $event)';
		$result = $this->exec($string, $replacements);
		$expected = 'public function beforeFilter(\Cake\Event\EventInterface $event)';
		$this->assertSame($expected, $result);

		$string = 'public function beforeFilter(\Cake\Event\EventInterface $event): void';
		$result = $this->exec($string, $replacements);
		$this->assertSame($string, $result);

		$string = 'public function beforeFilter(EventInterface $event): void';
		$result = $this->exec($string, $replacements);
		$this->assertSame($string, $result);
	}

	/**
	 * @return void
	 */
	public function testPlugins(): void {
		$replacements = (new ControllerSnippets())->plugins();
		$string = '$this->loadComponent(\'Search.Prg\');';
		$result = $this->exec($string, $replacements);
		$expected = '$this->loadComponent(\'Search.Search\');';
		$this->assertSame($expected, $result);
	}

	/**
	 * @param string $string
	 * @param array $replacements
	 *
	 * @return string
	 */
	protected function exec(string $string, array $replacements): string {
		return $this->_updateContents($string, $replacements);
	}

}

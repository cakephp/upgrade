<?php

namespace Cake\Upgrade\Test\TestCase\Snippets\MethodSignatures;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Snippets\MethodSignatures\PluginsSnippets;

class PluginsSnippetsTest extends TestCase {

	use ChangeTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new PluginsSnippets())->snippets();
		$string = '';
		$result = $this->exec($string, $replacements);
		$expected = '';
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

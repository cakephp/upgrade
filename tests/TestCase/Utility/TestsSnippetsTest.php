<?php

namespace Cake\Upgrade\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Utility\TestsSnippets;

class TestsSnippetsTest extends TestCase {

	use ChangeTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new TestsSnippets())->snippets();

		$string = 'public function setUp()';
		$result = $this->exec($string, $replacements);
		$expected = 'public function setUp(): void';
		$this->assertSame($expected, $result);

		$result = $this->exec($expected, $replacements);
		$this->assertSame($expected, $result);

		$string = '$this->assertNotContains(';
		$result = $this->exec($string, $replacements);
		$expected = '$this->assertStringNotContainsString(';
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

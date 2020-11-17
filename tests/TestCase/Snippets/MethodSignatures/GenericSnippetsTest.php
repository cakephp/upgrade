<?php

namespace Cake\Upgrade\Test\TestCase\Snippets\MethodSignatures;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Snippets\MethodSignatures\GenericSnippets;

class GenericSnippetsTest extends TestCase {

	use ChangeTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new GenericSnippets())->snippets();

		$string = 'public function routes($routes)';
		$result = $this->exec($string, $replacements);
		$expected = 'public function routes(\Cake\Routing\RouteBuilder $routes): void';
		$this->assertSame($expected, $result);

		$string = 'public function routes(RouteBuilder $routes)';
		$result = $this->exec($string, $replacements);
		$expected = 'public function routes(RouteBuilder $routes): void';
		$this->assertSame($expected, $result);

		$result = $this->exec($expected, $replacements);
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

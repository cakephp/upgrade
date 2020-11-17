<?php

namespace Cake\Upgrade\Test\TestCase\Snippets\MethodSignatures;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Snippets\MethodSignatures\FormSnippets;

class FormSnippetsTest extends TestCase {

	use ChangeTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new FormSnippets())->snippets();

		$string = 'protected function _buildSchema(Schema $schema)';
		$result = $this->exec($string, $replacements);
		$expected = 'protected function _buildSchema(Schema $schema): Schema';
		$this->assertSame($expected, $result);

		$result = $this->exec($expected, $replacements);
		$this->assertSame($expected, $result);

		$string = 'protected function _execute(array $data)';
		$result = $this->exec($string, $replacements);
		$expected = 'protected function _execute(array $data): bool';
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

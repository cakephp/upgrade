<?php

namespace Cake\Upgrade\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Utility\TableSnippets;

class TableSnippetsTest extends TestCase {

	use ChangeTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new TableSnippets())->events();

		$string = 'public function buildRules(RulesChecker $rules)';
		$result = $this->exec($string, $replacements);
		$expected = 'public function buildRules(RulesChecker $rules): RulesChecker';
		$this->assertSame($expected, $result);

		$result = $this->exec($expected, $replacements);
		$this->assertSame($expected, $result);

		$string = 'protected function _initializeSchema(TableSchema $schema): TableSchema';
		$result = $this->exec($string, $replacements);
		$expected = 'protected function _initializeSchema(\Cake\Database\Schema\TableSchemaInterface $schema): \Cake\Database\Schema\TableSchemaInterface';
		$this->assertSame($expected, $result);

		$string = 'protected function _initializeSchema(TableSchema $schema)';
		$result = $this->exec($string, $replacements);
		$expected = 'protected function _initializeSchema(\Cake\Database\Schema\TableSchemaInterface $schema): \Cake\Database\Schema\TableSchemaInterface';
		$this->assertSame($expected, $result);

		$string = 'protected function _initializeSchema(TableSchemaInterface $schema)';
		$result = $this->exec($string, $replacements);
		$expected = 'protected function _initializeSchema(TableSchemaInterface $schema): \Cake\Database\Schema\TableSchemaInterface';
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

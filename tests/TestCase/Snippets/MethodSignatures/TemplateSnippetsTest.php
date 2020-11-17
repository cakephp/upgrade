<?php

namespace Cake\Upgrade\Test\TestCase\Snippets\MethodSignatures;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\ChangeTrait;
use Cake\Upgrade\Snippets\MethodSignatures\ExecTrait;
use Cake\Upgrade\Snippets\MethodSignatures\TemplateSnippets;

class TemplateSnippetsTest extends TestCase {

	use ChangeTrait;
	use ExecTrait;

	/**
	 * @return void
	 */
	public function testReplace(): void {
		$replacements = (new TemplateSnippets())->snippets();

		$string = '->_getViewFileName(';
		$result = $this->exec($string, $replacements);
		$expected = '->_getTemplateFileName(';
		$this->assertSame($expected, $result);
	}

}

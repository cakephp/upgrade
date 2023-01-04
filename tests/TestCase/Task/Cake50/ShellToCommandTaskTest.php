<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\ShellToCommandTask;

class ShellToCommandTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;
		$filePath = $path . 'src' . DS . 'Command' . DS . 'SomeCommand.php';

		$task = new ShellToCommandTask(['path' => $path]);
		$task->run($filePath);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
src/Command/SomeCommand.php
-    public function simple($vis = Shell::VERBOSE) {
-        return Shell::CODE_ERROR;
+    public function simple($vis = \Cake\Console\ConsoleIo::VERBOSE) {
+        return \Cake\Command\Command::CODE_ERROR;

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}

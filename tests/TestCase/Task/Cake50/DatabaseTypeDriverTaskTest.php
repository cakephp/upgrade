<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\DatabaseTypeDriverTask;

class DatabaseTypeDriverTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;
		$filePath = $path . 'src' . DS . 'Database' . DS . 'Type' . DS . 'SomeType.php';

		$task = new DatabaseTypeDriverTask(['path' => $path]);
		$task->run($filePath);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
src/Database/Type/SomeType.php
-use Cake\Database\DriverInterface;
+use Cake\Database\Driver;
-     * @param \Cake\Database\DriverInterface $driver Driver.
+     * @param \Cake\Database\Driver $driver Driver.
-    public function toDatabase($value, DriverInterface $driver) {
+    public function toDatabase(mixed $value, Driver $driver): mixed {
-     * @param \Cake\Database\DriverInterface $driver Driver.
+     * @param \Cake\Database\Driver $driver Driver.
-    public function toStatement($value, DriverInterface $driver) {
+    public function toStatement(mixed $value, Driver $driver): int {
-    public function marshal($value) {
+    public function marshal(mixed $value): mixed {

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}

<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\TypedClosureTask;

class TypedClosureTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;
		$filePath = $path . 'src' . DS . 'Plugin.php';

		$task = new TypedClosureTask(['path' => $path]);
		$task->run($filePath);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
src/Plugin.php
-        $routes->prefix('Admin', function (RouteBuilder $routes) {
-            $routes->plugin('Expose', function (RouteBuilder $routes) {
+        $routes->prefix('Admin', function (RouteBuilder $routes): void {
+            $routes->plugin('Expose', function (RouteBuilder $routes): void {

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}

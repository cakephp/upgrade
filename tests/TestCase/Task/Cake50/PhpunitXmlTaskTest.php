<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\PhpunitXmlTask;

class PhpunitXmlTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;

		$task = new PhpunitXmlTask(['path' => $path, 'skipSchemaCheck' => true]);
		$task->run($path);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
phpunit.xml.dist
-<phpunit
-    colors="true"
-    bootstrap="tests/bootstrap.php"
-    >
+<phpunit bootstrap="tests/bootstrap.php" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd">
+
+        <env name="FIXTURE_SCHEMA_METADATA" value="tests/schema.php"/>
-    <listeners>
-        <listener class="Cake\TestSuite\Fixture\FixtureInjector">
-            <arguments>
-                <object class="Cake\TestSuite\Fixture\FixtureManager"/>
-            </arguments>
-        </listener>
-    </listeners>
+    <extensions>
+        <extension class="\Cake\TestSuite\Fixture\PHPUnitExtension"/>
+    </extensions>

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}

<?php

use Cake\TestSuite\TestCase;

class Foo extends TestCase {

	public function test() {
		$this->getMock('Cake\Foo\Bar');

		$this->getMock('Cake\Foo\Bar', ['method'], ['argument']);
	}

}

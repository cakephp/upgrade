<?php

use Cake\TestSuite\TestCase;

class Foo extends TestCase {

	public function test() {
		$this->getMockBuilder('Cake\Foo\Bar')->getMock();

		$this->getMock('Cake\Foo\Bar', ['method'], ['argument']);
	}

}

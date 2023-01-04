<?php

namespace SomePlugin\Test\TestCase;
use Cake\Controller\Controller;

class SomeControllerTest {

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->useCommandRunner();

		$this->Controller = new Controller();
	}

}

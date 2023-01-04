<?php

namespace SomePlugin\Test\TestCase;
class SomeCommandTest
{
	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->useCommandRunner();
	}

}

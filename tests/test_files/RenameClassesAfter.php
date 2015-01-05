<?php

use Cake\Network\Http\Client;
use Tools\Lib\HttpSocketLib;

class RenameClasses {

/**
 * @return void
 */
	public function testing() {
		$this->Client = new Client();
		$this->Text = new Text();
		$this->CustomText = new MyString();
		$result = Text::foo();
		$customResult = MyString::foo();

		$result = CustomHttpSocket::foo();
	}

}

use Cake\TestSuite\TestCase;

class SomeTestCase extends TestCase {
}

use App\TestSuite\CustomCakeTestCase;

class SomeCustomTestCase extends CustomCakeTestCase {
}

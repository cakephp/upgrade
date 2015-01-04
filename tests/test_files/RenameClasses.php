<?php

use Cake\Network\Http\HttpSocket;
use Tools\Lib\HttpSocketLib;

class RenameClasses {

/**
 * @return void
 */
	public function testing() {
		$this->Client = new HttpSocket();
		$this->Text = new String();
		$this->CustomText = new MyString();
		$result = String::foo();
		$customResult = MyString::foo();

		$result = CustomHttpSocket::foo();
	}

}

use Cake\TestSuite\CakeTestCase;

class SomeTestCase extends CakeTestCase {
}

use App\TestSuite\CustomCakeTestCase;

class SomeCustomTestCase extends CustomCakeTestCase {
}

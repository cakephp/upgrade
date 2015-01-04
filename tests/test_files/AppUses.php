<?php

namespace Foo;

App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');
App::uses('Component', 'Controller');
App::uses('SomeLib', 'Data.Lib');
App::uses('CurrencyLib', 'PluginName.Lib/Currency');
App::uses('FooShell', 'MyPlugin.Console/Command');

class AppUsesTest {

	public function test() {
		$this->Controller = new Controller(new CakeRequest, new CakeResponse());
	}

}

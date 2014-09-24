<?php

class Foo {

	public function test() {
		$x = __("Some %s text", $y);
		$x = __d('Domain', "Some %s %s text %s", $a, $b, $c);
	}

}
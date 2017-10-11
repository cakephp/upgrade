<?php

class Foo {

	public function test() {
		$x = __('Some {0} text', $y);
		$x = __d('Domain', 'Some {0} {1} text {2}', $a, $b, $c);
	}

}

<?php

namespace Foo;

class AppUsesImplicit extends AppController implements Controller {

	public function test() {
		$url = Router::url('xyz');
	}

}

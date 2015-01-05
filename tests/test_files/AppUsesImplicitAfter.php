<?php

namespace Foo;

use App\Controller\AppController;
use Cake\Controller\Controller;
use Cake\Routing\Router;

class AppUsesImplicit extends AppController implements Controller {

	public function test() {
		$url = Router::url('xyz');
	}

}

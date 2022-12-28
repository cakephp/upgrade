<?php

namespace Cake\Upgrade\Test\test_files\Task\Cake50\src\Controller;
class SomeController {

	public function simple() {
		$modelName = $this->getController()->loadModel()->getAlias();
	}

	public function multiLine() {
		$modelName = $this->X->loadModel()
			->getAlias();
	}

}

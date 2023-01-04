<?php

namespace SomePlugin\Controller;
class SomeController {

	public function simple() {
		$modelName = $this->getController()->loadModel()->getAlias();
	}

	public function multiLine() {
		$modelName = $this->X->loadModel()
			->getAlias();
	}

}

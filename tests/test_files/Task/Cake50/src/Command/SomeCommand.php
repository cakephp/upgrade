<?php

namespace SomePlugin\Command;

use Cake\Console\Shell;

class SomeCommand {

	public function simple($vis = Shell::VERBOSE) {
		return Shell::CODE_ERROR;
	}

}

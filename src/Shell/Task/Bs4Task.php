<?php

namespace Cake\Upgrade\Shell\Task;

/**
 * Handles BS4 templating. Runs over CakePHP 4 template code.
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 * @link http://upgrade-bootstrap.bootply.com/
 * @link
 */
class Bs4Task extends BaseTask {

	use ChangeTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Processes a path.
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _process($path) {
		$patterns = [
			[
				'label to badge',
				'/blabel label-/',
				'badge badge-',
			],
			[
				'col-xs-* to col-*',
				'/\bcol-xs-/',
				'col-',
			],
		];

		$original = $contents = $this->Stage->source($path);

		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * _shouldProcess
	 *
	 * Bail for invalid files (php/ctp files only)
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _shouldProcess($path) {
		$ending = substr($path, -4);

		return $ending === '.php';
	}

}

<?php

namespace Cake\Upgrade\Shell\Task;

/**
 * Updates test cases for 3.0
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class TestsTask extends BaseTask {

	use ChangeTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Process tests regarding mock usage and update it for 3.x
	 *
	 * @param string $path Path
	 * @return bool
	 */
	protected function _process($path) {
		$original = $contents = $this->Stage->source($path);

		$contents = $this->_replaceMock($contents);

		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * @param string $contents
	 *
	 * @return string
	 */
	protected function _replaceMock($contents) {
		$processor = function ($matches) {
			return '$this->getMockBuilder(\'' . $matches[1] . '\')->getMock()';
		};

		$contents = preg_replace_callback(
			'#\$this-\>getMock\(\'(.+?)\'\)#msi',
			$processor,
			$contents,
			-1,
			$count
		);

		return $contents;
	}

}

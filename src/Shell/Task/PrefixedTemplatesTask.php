<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link http://cakephp.org CakePHP(tm) Project
 * @since 3.0.0
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Upgrade\Shell\Task;

use Cake\Console\ConsoleOptionParser;
use Cake\Utility\Inflector;

/**
 * Move prefix_action.ctp to Prefix/action.ctp
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class PrefixedTemplatesTask extends BaseTask {

	use ChangeTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Process
	 *
	 * Moves view templates for given routing prefix
	 *
	 * e.g.
	 * Move admin_action.ctp to Admin/action.ctp
	 * Extract admin_ prefixed actions from controller and create new controller in Admin subfolder
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _process($path) {
		$new = str_replace(
			'Template' . DS,
			'Template' . DS . Inflector::camelize($this->params['prefix']) . DS,
			$path
		);
		$new = str_replace($this->params['prefix'] . '_', '', $new);

		return $this->Stage->move($path, $new);
	}

	/**
	 * _shouldProcess
	 *
	 * Only process .ctp files for current prefix
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _shouldProcess($path) {
		return strpos($path, 'Template' . DS) &&
			substr($path, -4) === '.ctp' &&
			strpos($path, $this->params['prefix'] . '_') &&
			strpos($path, 'Template' . DS . 'Element' . DS) === false &&
			strpos($path, 'Template' . DS . 'Email' . DS) === false &&
			strpos($path, 'Template' . DS . 'Error' . DS) === false &&
			strpos($path, 'Template' . DS . 'Layout' . DS) === false;
	}

	/**
	 * Get the option parser for this shell.
	 *
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser(): ConsoleOptionParser {
		return parent::getOptionParser()
			->addOptions([
				'prefix' => [
					'help' => 'Routing prefix to migrate.',
					'default' => 'admin',
				],
			]);
	}

}

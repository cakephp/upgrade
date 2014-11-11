<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Shell\Task;

use Cake\Upgrade\Shell\Task\BaseTask;

/**
 * Update method names task.
 *
 * Handles updating method names that have been changed.
 *
 */
class UpdateMethodNamesTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * Processes a path.
 *
 * @param string $path
 * @return void
 */
	protected function _process($path) {
		$helperPatterns = [
			[
				'Replace $this->Paginator->url() with $this->Paginator->generateUrl()',
				'#\$this->Paginator->url\(#',
				'$this->Paginator->generateUrl(',
			],
			[
				'Replace $this->Html->url() with $this->Url->build()',
				'#\$this->Html->url\(#',
				'$this->Url->build(',
			],
			[
				'Replace $this->Html->assetTimestamp() with $this->Url->assetTimestamp()',
				'#\$this->Html->assetTimestamp\(#',
				'$this->Url->assetTimestamp(',
			],
			[
				'Replace $this->Html->assetUrl() with $this->Url->assetUrl()',
				'#\$this->Html->assetUrl\(#',
				'$this->Url->assetUrl(',
			],
			[
				'Replace $this->Html->webroot() with $this->Url->webroot()',
				'#\$this->Html->webroot\(#',
				'$this->Url->webroot(',
			],
		];

		$otherPatterns = [
			[
				'Replace $this->Cookie->type() with $this->Cookie->encryption()',
				'#\$this->Cookie->type\(#',
				'$this->Cookie->encryption(',
			],
			[
				'Replace ConnectionManager::getDataSource() with ConnectionManager::get()',
				'#ConnectionManager\:\:getDataSource\(#',
				'ConnectionManager::get(',
			],
		];

		$taskPatterns = [
			[
				'Replace function execute() with main()',
				'#function execute\(\)#',
				'function main()',
			],
			[
				'Replace parent::execute() with parent::main()',
				'#parent\:\:execute\(\)#',
				'parent::main()',
			],
			[
				'Replace calls to execute() with main()',
				'#->execute\(#',
				'->main(',
			],
		];

		$patterns = [];
		if (
			strpos($path, DS . 'Template' . DS) !== false ||
			strpos($path, DS . 'View' . DS) !== false
		) {
			$patterns = $helperPatterns;
		}
		if (strpos($path, DS . 'Command' . DS . 'Task' . DS)) {
			$patterns = $taskPatterns;
		}
		$patterns = array_merge($patterns, $otherPatterns);

		$original = $contents = $this->Stage->source($path);
		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

}

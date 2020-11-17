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

/**
 * Renames classes
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class RenameClassesTask extends BaseTask {

	use ChangeTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Rename the classes in a given file.
	 *
	 * @param string $path The path to operate on.
	 * @return bool
	 */
	protected function _process($path) {
		$replacements = [
			'Cake\Network\Http\HttpSocket' => 'Cake\Network\Http\Client',
			'Cake\Model\ConnectionManager' => 'Cake\Database\ConnectionManager',
			'Cake\TestSuite\CakeTestCase' => 'Cake\TestSuite\TestCase',
			'Cake\TestSuite\Fixture\CakeTestFixture' => 'Cake\TestSuite\Fixture\TestFixture',
			'Cake\Utility\String' => 'Cake\Utility\Text',
			'CakePlugin' => 'Plugin',
			'CakeException' => '\Exception',
		];

		$original = $contents = $this->Stage->source($path);

		// Replace class name as it is
		$contents = str_replace(
			array_keys($replacements),
			array_values($replacements),
			$contents
		);

		// Replace static and dynamic calls
		foreach ($replacements as $oldName => $newName) {
			$oldNamePos = strrpos($oldName, '\\');
			$newNamePos = strrpos($newName, '\\');
			if ($oldNamePos !== false) {
				$oldName = substr($oldName, $oldNamePos + 1);
			}
			if ($newNamePos !== false) {
				$newName = substr($newName, $newNamePos + 1);
			}

			$contents = preg_replace('#\b(new|extends|implements) ' . $oldName . '\b#i', '\1 ' . $newName, $contents);

			$contents = preg_replace('#\b' . $oldName . '::#i', $newName . '::', $contents);
		}

		return $this->Stage->change($path, $original, $contents);
	}

}

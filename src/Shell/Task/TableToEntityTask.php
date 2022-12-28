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
 * Make Table classes build Entity classes.
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class TableToEntityTask extends BaseTask {

	use ChangeTrait;
	use HelperTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Check all moves, and stage moving the file to new location.
	 *
	 * @param mixed $path
	 * @return bool
	 */
	protected function _process($path) {
		$normalizedPath = str_replace(DS, '/', $path);

		if (!preg_match('#/Model/Table/([a-z0-9]+?)Table\.php#i', $normalizedPath, $matches)) {
			return false;
		}
		$modelClass = $matches[1];

		$entityClass = Inflector::singularize($modelClass);

		$new = str_replace(DS . 'Model' . DS . 'Table' . DS . $modelClass . 'Table', DS . 'Model' . DS . 'Entity' . DS . $entityClass, $path);
		if (file_exists($new)) {
		    return false;
		}

		$namespace = $this->_getNamespace();

		$content = <<<TXT
<?php
namespace $namespace\Model\Entity;

use Tools\Model\Entity\Entity;

/**
 * @property string \$id
 */
class $entityClass extends Entity {

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected \$_accessible = [
        '*' => true,
        'id' => false,
    ];

}

TXT;

		$dir = dirname($new);
		if (!is_dir($dir)) {
			mkdir($dir, 0664, true);
		}

		return (bool)file_put_contents($new, $content);
	}

	/**
	 * _shouldProcess
	 *
	 * Is the current path within the scope of any move?
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _shouldProcess($path) {
		$relativeFromRoot = $this->_getRelativePath($path);

		if (strpos($relativeFromRoot, DS . 'Plugin' . DS) || strpos($relativeFromRoot, DS . 'plugins' . DS)) {
			return false;
		}
		if (strpos($relativeFromRoot, DS . 'Vendor' . DS) || strpos($relativeFromRoot, DS . 'vendors' . DS)) {
			return false;
		}

		$from = 'Model' . DS . 'Table' . DS;
		if (strpos($relativeFromRoot, DS . $from) !== false) {
			return true;
		}

		return false;
	}

	/**
	 * Get the option parser for this shell.
	 *
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser(): ConsoleOptionParser {
		return parent::getOptionParser()
			->addOptions([
				'root' => [
					'default' => '',
					'help' => 'Set an application\'s root path. Not defining it makes the current path the root one.',
				],
			]);
	}

	/**
	 * @return string
	 */
	protected function _getNamespace() {
		$ns = $this->param('namespace');
		if (!$ns) {
			$ns = 'App';
		}

		return $ns;
	}

}

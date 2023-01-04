<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - protected $_defaultConfig => protected array $_defaultConfig
 */
class TypedPropertyPluginTask extends Task implements RepoTaskInterface {

	/**
	 * @var array<string>
	 */
	protected const PROPERTIES = [
		'bootstrapEnabled',
		'consoleEnabled',
		'middlewareEnabled',
		'servicesEnabled',
		'routesEnabled',
	];

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . 'src' . DS . 'Plugin.php';
		if (!file_exists($filePath)) {
			return;
		}

		$from = $to = [];
		foreach (static::PROPERTIES as $property) {
			$from[] = 'protected $' . $property . ' =';
			$to[] = 'protected bool $' . $property . ' =';
		}

		$content = (string)file_get_contents($filePath);
		$newContent = str_replace($from, $to, $content);

		$this->persistFile($filePath, $content, $newContent);
	}

}

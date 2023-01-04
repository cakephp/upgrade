<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - Upgrades <filter> to <coverage>
 * - Adds <env name="FIXTURE_SCHEMA_METADATA" value="tests/schema.php"/> if needed
 * - Replaces <listeners> with <extensions> //FIXME
 * - TODO: Header attr? xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd etc
 */
class PhpcsPsr2rTask extends Task implements RepoTaskInterface {

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . 'phpcs.xml';
		if (!file_exists($filePath)) {
			return;
		}

		$content = (string)file_get_contents($filePath);
		$newContent = $this->replaceCs($path, $content);

		$this->persistFile($filePath, $content, $newContent);
	}

	/**
	 * @param string $path
	 * @param string $content
	 *
	 * @return string
	 */
	protected function replaceCs(string $path, string $content): string {
		if (strpos($content, '<rule ref="SlevomatCodingStandard.TypeHints.ParameterTypeHint">') !== false) {
			return $content;
		}

		$xmlData = file_get_contents(ROOT . DS . 'resources' . DS . 'phpcs.xml');

		preg_match('#^(\s+)\<rule ref="PSR2R"/\>#mu', $content, $matches);
		$indentation = $matches ? $matches[1] : '    ';

		$replace = $indentation . '<rule ref="PSR2R"/>' . PHP_EOL . $xmlData;
		$content = preg_replace('#^(\s+)\<rule ref="PSR2R"/\>#mu', $replace, $content);

		return $content;
	}

}

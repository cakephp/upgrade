<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - Upgrades <filter> to <coverage>
 * - Adds <env name="FIXTURE_SCHEMA_METADATA" value="tests/schema.php"/> if needed
 * - TODO: Header attr? xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd etc
 */
class PhpunitXmlTask extends Task implements RepoTaskInterface {

	/**
	 * @var string
	 */
	protected const FILE_SCHEMA = 'tests/schema.php';

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . 'phpunit.xml.dist';
		if (!file_exists($filePath)) {
			return;
		}

		if (empty($this->config['skipSchemaCheck']) && !$this->hasSchemaFile($path)) {
			return;
		}

		$content = (string)file_get_contents($filePath);
		if (strpos($content, '<env name="FIXTURE_SCHEMA_METADATA"') !== false) {
			return;
		}

		preg_match('#^(\s+)\</php\>#mu', $content, $matches);
		$indentation = $matches ? $matches[1] : '    ';

		$replace = PHP_EOL . $indentation . $indentation . '<env name="FIXTURE_SCHEMA_METADATA" value="tests/schema.php"/>' . PHP_EOL . $indentation . '</php>';
		$newContent = preg_replace('#^(\s+)\</php\>#mu', $replace, $content);

		/*
		$xml = Xml::build($content);

		$array = Xml::toArray(Xml::build($content));
		//dd(Xml::fromArray($array, ['pretty' => true])->asXML());
		$xml = $xml->asXML();
		dd($xml);
		*/

		$this->persistFile($filePath, $content, $newContent);
	}

	/**
	 * @param array<string> $files
	 *
	 * @return bool
	 */
	protected function containsFixtures(array $files): bool {
		foreach ($files as $file) {
			$content = file_get_contents($file);
			if (strpos($content, 'protected $fixtures = [') || strpos($content, 'protected array $fixtures = [')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	protected function hasSchemaFile(string $path): bool {
		$schemaFilePath = $path . static::FILE_SCHEMA;

		return file_exists($schemaFilePath);
	}

}

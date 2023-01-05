<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - Upgrades <filter> to <coverage>
 * - Adds <env name="FIXTURE_SCHEMA_METADATA" value="tests/schema.php"/> if needed
 * - Replaces <listeners> with <extensions>
 * - Fixes up header attr: xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd etc
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

		$content = (string)file_get_contents($filePath);
		$newContent = $this->replaceListenerWithExtension($content);

		$newContent = $this->replaceHeader($newContent);
		$newContent = $this->addFixtureSchemadataPath($path, $newContent);

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
	 * @param string $path
	 * @param string $content
	 *
	 * @return string
	 */
	protected function addFixtureSchemadataPath(string $path, string $content): string {
		if (empty($this->config['skipSchemaCheck']) && !$this->hasSchemaFile($path)) {
			return $content;
		}

		if (strpos($content, '<env name="FIXTURE_SCHEMA_METADATA"') !== false) {
			return $content;
		}

		preg_match('#^(\s+)\</php\>#mu', $content, $matches);
		$indentation = $matches ? $matches[1] : '    ';

		$replace = PHP_EOL . $indentation . $indentation . '<env name="FIXTURE_SCHEMA_METADATA" value="tests/schema.php"/>' . PHP_EOL . $indentation . '</php>';
		$content = preg_replace('#^(\s+)\</php\>#mu', $replace, $content);

		return $content;
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

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	protected function replaceListenerWithExtension(string $content): string {
		$rows = explode(PHP_EOL, $content);
		$startRow = $this->findStartRow($rows, '<listeners>');
		$endRow = $this->findEndRow($rows, '</listeners>');
		if ($startRow === null || $endRow === null) {
			return $content;
		}

		$indentation = $this->indentation($rows[$startRow]);

		for ($i = $startRow; $i <= $endRow; $i++) {
			unset($rows[$i]);
		}

		$extension = <<<TXT
$indentation<extensions>
$indentation$indentation<extension class="\Cake\TestSuite\Fixture\PHPUnitExtension"/>
$indentation</extensions>
TXT;
		$extensionRows = explode(PHP_EOL, $extension);

		array_splice($rows, $startRow, 0, $extensionRows);

		return implode(PHP_EOL, $rows);
	}

	/**
	 * @param array<string> $rows
	 * @param string $needle
	 *
	 * @return int|null
	 */
	protected function findStartRow(array $rows, string $needle): ?int {
		foreach ($rows as $i => $row) {
			if (strpos($row, $needle) !== false) {
				return $i;
			}
		}

		return null;
	}

	/**
	 * @param array<string> $rows
	 * @param string $needle
	 * @param int|null $offset
	 *
	 * @return int|null
	 */
	protected function findEndRow(array $rows, string $needle, ?int $offset = null): ?int {
		foreach ($rows as $i => $row) {
			if ($offset !== null && $i < $offset) {
				continue;
			}

			if (strpos($row, $needle) !== false) {
				return $i;
			}
		}

		return null;
	}

	/**
	 * @param string $row
	 *
	 * @return string
	 */
	protected function indentation(string $row): string {
		preg_match('#^\s+#', $row, $matches);

		return $matches ? $matches[0] : '';
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	protected function replaceHeader(string $content): string {
		if (strpos($content, 'https://schema.phpunit.de/9.3/phpunit.xsd') !== false) {
			return $content;
		}

		$replacement = <<<'TXT'
<phpunit bootstrap="tests/bootstrap.php" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd">
TXT;

		$rows = explode(PHP_EOL, $content);
		$startRow = $this->findStartRow($rows, '<phpunit');
		$endRow = $this->findEndRow($rows, '>', $startRow);
		if ($startRow === null || $endRow === null) {
			return $content;
		}

		for ($i = $startRow; $i <= $endRow; $i++) {
			unset($rows[$i]);
		}

		$replacementRows = explode(PHP_EOL, $replacement);

		array_splice($rows, $startRow, 0, $replacementRows);

		$content = implode(PHP_EOL, $rows);

		return $content;
	}

}

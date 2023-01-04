<?php

namespace Cake\Upgrade\Task;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

abstract class Task {

	protected array $config;

	protected array $changes = [];

	/**
	 * @param array $config
	 */
	public function __construct(array $config = []) {
		$this->config = $config + [
			'dry-run' => true,
			'path' => null,
		];
	}

	/**
	 * @return bool
	 */
	public function hasChanges(): bool {
		return (bool)$this->changes;
	}

	/**
	 * @return \Cake\Upgrade\Task\ChangeSet
	 */
	public function getChanges(): ChangeSet {
		return new ChangeSet($this->changes);
	}

	/**
	 * @param string $filePath
	 * @param string $content
	 * @param string $newContent
	 *
	 * @return void
	 */
	protected function persistFile(string $filePath, string $content, string $newContent) {
		if (!file_exists($filePath) && $content !== '') {
			throw new RuntimeException('Cannot update a non-existent file `' . $filePath . '`');
		}

		if ($content === $newContent) {
			return;
		}

		$this->changes[] = new Change($filePath, $content, $newContent, $this->config);

		if ($this->config['dry-run']) {
			return;
		}

		file_put_contents($filePath, $newContent);
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	protected function path(string $path): string {
		if (!$this->config['path']) {
			return $path;
		}

		return str_replace($this->config['path'], '', $path);
	}

	/**
	 * @param string $path
	 * @param string|null $ext
	 * @param array $subPaths
	 *
	 * @return array<string>
	 */
	protected function collectFiles(string $path, ?string $ext, array $subPaths): array {
		$files = [];

		foreach ($subPaths as $subPath) {
			if (!is_dir($path . $subPath)) {
				continue;
			}

			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($path . $subPath),
			);

			/** @var \SplFileInfo $file */
			foreach ($iterator as $file) {
				$filePath = $file->getPathname();
				if (!$file->isFile() || ($ext && $file->getExtension() !== $ext)) {
					continue;
				}

				$files[] = $filePath;
			}
		}

		return $files;
	}

}

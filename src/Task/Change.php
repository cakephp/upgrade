<?php

namespace Cake\Upgrade\Task;

use Cake\Upgrade\Utility\Differ;

class Change {

	protected string $path;

	protected string $before;

	protected string $after;

	protected array $config;

	/**
	 * @param string $path
	 * @param string $before
	 * @param string $after
	 * @param array $config
	 */
	public function __construct(string $path, string $before, string $after, array $config) {
		$this->path = $path;
		$this->before = $before;
		$this->after = $after;
		$this->config = $config;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		$diff = (new Differ())->diff($this->before, $this->after);
		$diff = str_replace("\t", '    ', $diff);

		$result = $this->path() . PHP_EOL;
		$result .= $diff . PHP_EOL;

		return $result;
	}

	/**
	 * @return string
	 */
	protected function path(): string {
		if (!$this->config['path']) {
			return $this->path;
		}

		return str_replace($this->config['path'], '', $this->path);
	}

}

<?php

namespace Cake\Upgrade\Utility;

abstract class AbstractSnippets {

	/**
	 * @var array
	 */
	protected $snippets = [];

	/**
	 * @param string $path
	 *
	 * @return array
	 */
	public function snippets(string $path): array {
		$list = [];

		foreach ($this->snippets as $name => $snippet) {

		}

		return $list;
	}

}

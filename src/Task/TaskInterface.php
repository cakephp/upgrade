<?php

namespace Cake\Upgrade\Task;

interface TaskInterface {

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void;

	/**
	 * @return bool
	 */
	public function hasChanges(): bool;

	/**
	 * @return \Cake\Upgrade\Task\ChangeSet
	 */
	public function getChanges(): ChangeSet;

}

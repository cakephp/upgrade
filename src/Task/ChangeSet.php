<?php

namespace Cake\Upgrade\Task;

use Countable;

class ChangeSet implements Countable {

	/**
	 * @var array<\Cake\Upgrade\Task\Change>
	 */
	protected array $changes;

	/**
	 * @param array $changes
	 */
	public function __construct(array $changes = []) {
		$this->changes = $changes;
	}

	/**
	 * @return array<\Cake\Upgrade\Task\Change>
	 */
	public function getChanges(): array {
		return $this->changes;
	}

	/**
	 * @param \Cake\Upgrade\Task\ChangeSet $changeSet
	 *
	 * @return void
	 */
	public function add(ChangeSet $changeSet): void {
		foreach ($changeSet->getChanges() as $change) {
			$this->changes[] = $change;
		}
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		$result = [];

		foreach ($this->changes as $change) {
			$result[] = (string)$change;
		}

		return implode(PHP_EOL, $result);
	}

	/**
	 * @return int
	 */
	public function count(): int {
		return count($this->changes);
	}

}

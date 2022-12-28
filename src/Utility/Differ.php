<?php declare(strict_types = 1);

namespace Cake\Upgrade\Utility;

use SebastianBergmann\Diff\Differ as SebastianBergmannDiffer;

class Differ {

	/**
	 * Aware of context (CLI vs Web)
	 *
	 * Note: HTML is auto-escaping all values for colors
	 * For non coloring make sure to manually escape.
	 *
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */
	public function coloredDiff(string $before, string $after): string {
		$differ = new SebastianBergmannDiffer();
		$array = $differ->diffToArray($before, $after);

		return $this->generateDiff($array, true);
	}

	/**
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */
	public function diff(string $before, string $after): string {
		$differ = new SebastianBergmannDiffer(null);
		$array = $differ->diffToArray($before, $after);

		return $this->generateDiff($array);
	}

	/**
	 * Generates diff for only changes lines.
	 *
	 * @param array<int, mixed> $array
	 * @param bool $colors
	 *
	 * @return string
	 */
	protected function generateDiff(array $array, bool $colors = false): string {
		$begin = null;
		$end = null;
		foreach ($array as $key => $row) {
			if ($row[1] === 0) {
				continue;
			}

			if ($begin === null) {
				$begin = $key;
			}
			$end = $key;
		}
		if ($begin === null) {
			return '';
		}

		$firstLineOfOutput = $begin > 0 ? $begin - 1 : 0;
		$lastLineOfOutput = count($array) - 1 > $end ? $end + 1 : $end;

		$out = [];
		for ($i = $firstLineOfOutput; $i <= $lastLineOfOutput; $i++) {
			$row = $array[$i];

			$output = trim($row[0], "\n\r\0\x0B");

			if ($row[1] === 1) {
				$char = '+';
			} elseif ($row[1] === 2) {
				$char = '-';
			} else {
				continue;
			}

			$row = $char . $output;
			if ($colors) {
				if ($char === '+') {
					$row = PHP_SAPI === 'cli' ? $row : '<span class="diff-add">' . h($row) . '</span>';
				} elseif ($char === '-') {
					$row = PHP_SAPI === 'cli' ? $row : '<span class="diff-remove">' . h($row) . '</span>';
				}
			}

			$out[] = $row;
		}

		return implode(PHP_EOL, $out);
	}

}

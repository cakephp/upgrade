<?php declare(strict_types = 1);

namespace Cake\Upgrade\Utility;

use RuntimeException;

class ComposerJson {

	/**
	 * @var array<string>
	 */
	protected static $objectKeys = [
		'autoload',
		'autoload-dev',
		'require',
		'require-dev',
		'config',
		'scripts',
	];

	/**
	 * @param string $file
	 *
	 * @throws \RuntimeException
	 *
	 * @return array
	 */
	public static function fromFile(string $file): array {
		$content = file_get_contents($file);
		if ($content === false) {
			throw new RuntimeException('Cannot decode composer.json file');
		}

		return static::fromString($content);
	}

	/**
	 * @param string $string
	 *
	 * @throws \RuntimeException
	 *
	 * @return array
	 */
	public static function fromString(string $string): array {
		$array = json_decode($string, true);
		if (!$array) {
			throw new RuntimeException('Cannot decode composer.json content');
		}

		return $array;
	}

	/**
	 * @param string $composerJson
	 *
	 * @return string|null
	 */
	public static function indentation(string $composerJson): ?string {
		preg_match('#^(\s+)"(require|require-dev)":#mui', $composerJson, $matches);
		if (!$matches) {
			return null;
		}

		return $matches[1];
	}

	/**
	 * @param array $array
	 * @param string|null $indentation
	 *
	 * @throws \RuntimeException
	 *
	 * @return string
	 */
	public static function toString(array $array, ?string $indentation = null): string {
		$objectKeys = static::$objectKeys;
		foreach ($objectKeys as $objectKey) {
			if (!isset($array[$objectKey]) || !empty($array[$objectKey])) {
				continue;
			}

			$array[$objectKey] = (object)$array[$objectKey];
		}

		$json = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		if ($json === false) {
			throw new RuntimeException('Cannot encode fixed composer.json file');
		}

		if ($indentation !== null) {
			$json = str_replace('    ', $indentation, $json);
		}

		return $json . PHP_EOL;
	}

	/**
	 * @param string $file
	 * @param array $array
	 * @param string|null $indentation
	 *
	 * @throws \RuntimeException
	 *
	 * @return void
	 */
	public static function toFile(string $file, array $array, ?string $indentation = null): void {
		$json = static::toString($array, $indentation);

		$result = file_put_contents($file, $json);
		if ($result === false) {
			throw new RuntimeException('Cannot write to composer.json file');
		}
	}

	/**
	 * @param string $composerJson
	 *
	 * @return string
	 */
	public static function normalize(string $composerJson): string {
		$composerJson = str_replace(["\r\n", "\r"], "\n", $composerJson);

		return trim($composerJson) . PHP_EOL;
	}

}

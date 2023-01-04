<?php

namespace SomePlugin\Database\Type;

use Cake\Database\DriverInterface;

class SomeType {

	/**
	 * Casts given value from a PHP type to one acceptable by a database.
	 *
	 * @param mixed $value Value to be converted to a database equivalent.
	 * @param \Cake\Database\DriverInterface $driver Driver.
	 * @return mixed Given PHP type casted to one acceptable by a database.
	 */
	public function toDatabase($value, DriverInterface $driver) {
		if ($value !== null && !is_string($value)) {
			return null;
		}

		return $value;
	}

	/**
	 * Get the correct PDO binding type for Year data.
	 *
	 * @param mixed $value The value being bound.
	 * @param \Cake\Database\DriverInterface $driver Driver.
	 * @return int
	 */
	public function toStatement($value, DriverInterface $driver) {
		return PDO::PARAM_INT;
	}

	/**
	 * @param mixed $value The value to convert.
	 * @return mixed Converted value.
	 */
	public function marshal($value) {
		return $value;
	}

}

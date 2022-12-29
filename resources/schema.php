<?php

$tables = [];

/**
 * @var \DirectoryIterator<\DirectoryIterator> $ierator
 */
$ierator = new DirectoryIterator(__DIR__ . DS . 'Fixture');
foreach ($ierator as $file) {
	if (!preg_match('/(\w+)Fixture.php$/', (string)$file, $matches)) {
		continue;
	}

	$name = $matches[1];
	$tableName = \Cake\Utility\Inflector::underscore($name);
	$class = '{{namespace}}\\Test\\Fixture\\' . $name . 'Fixture';
	try {
		$object = (new ReflectionClass($class))->getProperty('fields');
	} catch (ReflectionException $e) {
		continue;
	}

	$array = $object->getDefaultValue();
	$constraints = $array['_constraints'] ?? [];
	$indexes = $array['_indexes'] ?? [];
	unset($array['_constraints'], $array['_indexes'], $array['_options']);
	$table = [
		'table' => $tableName,
		'columns' => $array,
		'constraints' => $constraints,
		'indexes' => $indexes,
	];
	$tables[$tableName] = $table;
}

return $tables;

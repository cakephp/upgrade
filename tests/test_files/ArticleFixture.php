<?php
/**
 * ArticleFixture
 */
class ArticleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
		'title' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'body' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1]
		],
		'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB']
	];

/**
 * Records
 *
 * @var array
 */
	public $records = [
		[
			'id' => 1,
			'title' => 'A title',
			'body' => 'A body',
			'created' => '2014-10-09 20:34:42',
			'modified' => '2014-10-09 20:34:42'
		],
	];

}

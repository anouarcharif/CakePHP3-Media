<?php
namespace Media\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class MediasFixture extends TestFixture {

	public $table = 'test_pictures';
	public $connection = 'test';
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'ref' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ref_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'file' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'position' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'ref' => array('column' => 'ref', 'unique' => 0),
			'ref_id' => array('column' => 'ref_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

    public $records = array(
        array(
            'id' => 1,
            'ref' => 'Member',
            'ref_id' => 1,
            'file' => 'testHelper.png',
            'position' => 1
        ),
        array(
            'id' => 2,
            'ref' => 'Member',
            'ref_id' => 1,
            'file' => 'test2.jpg',
            'position' => 1
        ),
        array(
            'id' => 3,
            'ref' => 'Member',
            'ref_id' => 2,
            'file' => 'test3.jpg',
            'position' => 2
        ),
    );

}

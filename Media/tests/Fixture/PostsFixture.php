<?php
namespace Media\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PostsFixture extends TestFixture {

			public $table = 'test_posts';
			public $connection = 'test';
            public $fields = array(
                    'id' => array('type' => 'integer', 'key' => 'primary'),
                    'name' => array('type' => 'string', 'length' => 255, 'null' => false),
                    'content' => 'text',
                    'online' => array('type' => 'integer', 'default' => '0', 'null' => false),
                    'created' => 'datetime',
                    'updated' => 'datetime',
                    'media_id'=> array('type' => 'integer', 'default' => '0', 'null' => false),
            );
            public $records = array(
                    array('id' => 1, 'name' => 'First Article', 'content' => 'First Article content', 'online' => '1', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31','media_id' => 1),
                    array('id' => 2, 'name' => 'Second Article', 'content' => 'Second Article content', 'online' => '1', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31','media_id' => 0),
                    array('id' => 3, 'name' => 'Third Article', 'content' => 'Third Article content', 'online' => '1', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31','media_id' => 0)
            );
 }

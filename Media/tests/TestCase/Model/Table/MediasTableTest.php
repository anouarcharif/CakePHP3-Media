<?php
namespace Media\Test\Model\Table;

use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\View;
use Cake\ORM\TableRegistry;

class PostWithWidthLimit extends Table{
   	public function initialize(array $config)
    {
    	$this->table("posts");
    	$this->addBehavior('Media.Media', 
            	[
            		'path' => 'img/%f',
		            'extensions' => array('jpg','png','pdf'),
		            'max_width'  => 500
				]
        );
    }
}
class PostWithValidWidthLimit extends Table{
		public function initialize(array $config)
    {
   		$this->table("posts");
    	$this->addBehavior('Media.Media', 
            	[
            		'path' => 'img/%f',
		            'extensions' => array('jpg','png','pdf'),
		            'max_width'  => 1500
				]
        );
    }
}
class PostWithLimit extends Table{
   	public function initialize(array $config)
    {
   		$this->table("posts");
    	$this->addBehavior('Media.Media', 
            	[
            		'path' => 'img/%f',
		            'extensions' => array('jpg','png','pdf'),
		            'limit'  => 1
				]
        );
    }
}
class PostWithSizeLimit extends Table {
    public function initialize(array $config)
    {
   		$this->table("posts");
    	$this->addBehavior('Media.Media', 
            	[
            		'path' => 'img/%f',
		            'extensions' => array('jpg','png','pdf'),
		            'size'  => 40
				]
        );
    }
}

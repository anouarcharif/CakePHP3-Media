<?php
namespace Media\Test\TestCase\Controller;

use Cake\TestSuite\TestCase;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Request;
use Cake\Network\Response;

class MediasControllerTest extends IntegrationTestCase {

    public $fixtures = array('plugin.media.medias');
	
	
	public function setUp()
    {
        parent::setUp();
        $this->Medias = TableRegistry::get('TestPictures');
        $this->image = ROOT . DS . 'plugins' . DS . 'Media' . DS . 'tests' . DS . 'testHelper.png';
       	$Medias = $this->getMock('MediasController', array('canUploadMedias'));
	    $Medias->expects($this->any())->method('move_uploaded_file')->will($this->returnCallback('test_move_uploaded_file'));
        $Medias->expects($this->any())->method('canUploadMedias')->will($this->returnValue(true));
        $this->controller = $this->getMock(
            'App\Controller\MediasController'
        );
        
    }
    public function testUpload() {
        $_FILES = array('file' => array('name' => 'testHelper.png','type' => 'image/png','tmp_name' => $this->image ,'error' => (int) 0,'size' => (int) 52085));
        $this->post('/media/Medias/upload/Members/1', $_FILES);
        $this->assertResponseSuccess();
        $media = $this->Medias->find('all', array(
            'conditions' => array('file LIKE' => "%testHelper.png%")
        ))->first();
        
        $this->assertEquals('Member', $media->ref);
        $this->assertEquals(1, $media->ref_id);
        $this->Medias->delete($media);
        $this->assertEquals(false, file_exists(WWW_ROOT.$media->file));
    }

    public function testUploadWrongFileType() {
        $_FILES = array('file' => array('name' => 'testHelper.csv','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085));
        $return = $this->post('/media/Medias/upload/Members/1', $_FILES);
        $this->assertResponseFailure();
    }
	
   
    public function testListing() {
        $this->get('/media/Medias/index/Members/2');
        $this->assertEquals(1, count($this->viewVariable('medias')));
        $this->assertEquals(1, count($this->viewVariable("thumbID")));
        
    }
	
    public function testOrder() {
        $data = array(
            'Media' => array(2 => array(1 => 0, 2 => 1)),
        	'refName' => 'Members'
        );
        $this->post('/media/Medias/order', $data);
        $medias = $this->Medias->find('list', array('keyField' => 'id', 'valueField' => 'position'))->toArray();
        $this->assertEquals(0,$medias[1]);
        $this->assertEquals(1,$medias[2]);
    }

    public function testThumb() {
        $this->get('/media/Medias/thumb/2');
        $ref = $this->Members->get(2);
        $this->assertEquals(2, $ref->media_id);
    }

    
     public function testNoFoundDelete() {
       	$this->get('/media/Medias/delete/5');
       	$this->assertResponseCode(404);
    }
	
    public function testForbiddenIndex() {
        $this->get('/media/Medias/index/Members/10');
        $this->assertResponseCode(404);
    }
	
    public function testForbiddenUpload() {
        $this->get('/media/testmedias/upload/Members/10');
        $this->assertResponseCode(404);
    }
	
    public function testForbiddenDelete() {
        $this->get('/media/Medias/delete/10');
        $this->assertResponseCode(404);
    }

    public function testForbiddenThumb() {
        $this->get('/media/Medias/thumb/10');
        $this->assertResponseCode(404);
    }

    public function testForbiddenOrder() {
        $data = array(
            	'Media' => array(2 => array(10 => 0)),
        		'refName' => 'Members',
        );
        $this->post('/media/Medias/order', $data);
        $this->assertResponseCode(404);
    }
}
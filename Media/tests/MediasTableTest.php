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


class MediasTest extends IntegrationTestCase {

    public $fixtures = array('plugin.media.posts', 'plugin.media.medias');

    public function setUp() {
        parent::setUp();
        
        $this->image = ROOT . DS . APP_DIR . DS . 'Plugin' . DS . 'Media' . DS . 'Test' . DS . 'testHelper.png';
        $this->Medias = TableRegistry::get('Medias', ['className' => 'Media\Model\Table\MediasTable']);
        //$this->Media->expects($this->any())->method('move_uploaded_file')->will($this->returnCallback('test_move_uploaded_file'));
    }

    public function testAfterFindType() {
        $media = $this->Medias->find('all')->first();
        $this->assertEquals('pic', $media->type());
		$media = $this->Medias->newEntity();
        $media->file = "lol.pdf";
        $this->Medias->save($media);
        $media = $this->Medias->get(1);
        print_r($media);exit;
        $this->assertEquals('pdf', $media->type());
    }

    public function testBeforeSaveUnknowModel() {
        /*$this->expectException('CakeException');
        $this->Medias->save(array(
            'ref' => 'Page',
            'ref_id' => 3,
        ));*/
    }

    public function testDelete() {
        copy($this->image, WWW_ROOT . 'testHelper.png');
        $this->assertEquals(true, file_exists(WWW_ROOT . 'testHelper.png'));
        $this->Media->delete(1);
        $this->assertEquals(false, file_exists(WWW_ROOT . 'testHelper.png'));
    }

    public function testBeforeSave() {
        $file = array('name' => 'testHelper.png','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085);
        $data = array(
        	'ref'    => 'Member',
            'ref_id' => 1,
            'file'   => $file
        );
        $media = $this->Medias->newEntity($data);
        $this->Medias->save($media, $data);
        $media = $this->Medias->read();
        $this->assertEqualss('Post', $media->ref);
        $this->assertEqualss(1, $media->ref_id);
        $this->assertEqualss(true, file_exists(WWW_ROOT . trim($media->file, '/')));
        $this->Medias->delete($media);
    }

    public function testBeforeSaveWithForbiddenExtension() {
        $file = array('name' => 'testHelper.csv','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085);
        $data = array(
            'ref'    => 'Member',
            'ref_id' => 1,
            'file'   => $file
        );
        $media = $this->Medias->newEntity($data);
        $save = $this->Medias->save($media, $data);
        $media = $this->Medias->read();
        $this->assertEqualss(false, $save);
        $this->Media->delete($media);
    }

   /* public function testBeforeSaveWithWidthLimit() {
        $file = array(
            'name' => 'testHelper.png',
            'type' => 'image/png',
            'tmp_name' => $this->image,
            'error' => (int) 0,
            'size' => (int) 52085
        );
        $save = $this->Media->save(array(
            'ref'    => 'PostWithWidthLimit',
            'ref_id' => 1,
            'file'   => $file
        ));
        $this->assertEqualss(false, $save);
    }

    public function testBeforeSaveWithValidWidthLimit() {
        $file = array(
            'name' => 'testHelper.png',
            'type' => 'image/png',
            'tmp_name' => $this->image,
            'error' => (int) 0,
            'size' => (int) 52085
        );
        $save =$this->Media->save(array(
            'ref'    => 'PostWithValidWidthLimit',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEqualss(true, !empty($save));
        $this->Media->delete($this->Media->id);
    }

    public function testBeforeSaveWithLimit() {
        $file = array(
            'name' => 'testHelper.png',
            'type' => 'image/png',
            'tmp_name' => $this->image,
            'error' => (int) 0,
            'size' => (int) 52085
        );
        $save =$this->Media->save(array(
            'ref'    => 'PostWithLimit',
            'ref_id' => 1,
            'file'   => $file
        ));
        $this->assertEqualss(false, empty($save));
        $firstid = $this->Media->id;
        $this->Media->create();
        $save =$this->Media->save(array(
            'ref'    => 'PostWithLimit',
            'ref_id' => 1,
            'file'   => $file
        ));
        $this->assertEqualss(false, $save);
        $this->Media->delete($firstid);
    }

    public function testBeforeSaveWithSizeLimit() {
        // Une image trop lourde (50ko > 40 ko)
        $file = array(
            'name' => 'testHelper.png',
            'type' => 'image/png',
            'tmp_name' => $this->image,
            'error' => (int) 0,
            'size' => (int) 52085
        );
        $save =$this->Media->save(array(
            'ref'    => 'PostWithSizeLimit',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEqualss(false, $save);

        // Une image à la bonne taille
        $file = array(
            'name' => 'testHelper.png',
            'type' => 'image/png',
            'tmp_name' => str_replace('.png', '50.png', $this->image),
            'error' => (int) 0,
            'size' => (int) 1955
        );
        $save =$this->Media->save(array(
            'ref'    => 'PostWithSizeLimit',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEqualss(true, !empty($save));
        $this->Media->delete($this->Media->id);
    }

    public function testDuplicate() {
        $file = array('name' => 'testHelper.png','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085);
        $this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $id = $this->Media->id;
        $media = $this->Media->read();

        $this->assertEqualss('testHelper.png', basename($media['Media']['file']));
        $this->Media->create();
        $media = $this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEqualss('testHelper-1.png', basename($media['Media']['file']));

        $this->Media->create();
        $media = $this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEquals('testHelper-2.png', basename($media['Media']['file']));

        $this->Media->delete($id);
        $this->Media->delete($id+1);
        $this->Media->delete($id+2);
    }*/

}
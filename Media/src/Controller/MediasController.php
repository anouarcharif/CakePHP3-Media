<?php

namespace Media\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\ForbiddenException;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\Collection\Collection;

class MediasController extends AppController {

    public $order = ['Medias.position ASC'];
    public $helpers = ['Html'];

   public function isAuthorized($user = null)
   {
        return true;
   }

    public function canUploadMedias($ref, $ref_id)
    {
        if(method_exists('App\Controller\AppController', 'canUploadMedias')){
            return parent::canUploadMedias($ref, $ref_id);
        }else{
            return false;
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->loadModel("Media.Medias");
        $this->layout = 'uploader';
        if(in_array('Security', $this->components)){
            $this->Security->unlockedActions = array('upload', 'order','index','delete','thumb');
        }
    }

    /**
    * Liste les médias
    **/
    
    public function index($ref, $ref_id)
    {
        if(!$this->canUploadMedias($ref, $ref_id)){
            throw new ForbiddenException();
        }
        $this->loadModel($ref);
        $this->set(compact('ref', 'ref_id'));
        if(!in_array('Media', $this->$ref->Behaviors()->loaded())){
            return $this->render('nobehavior');
        }
        $id = isset($this->request->query['id']) ? $this->request->query['id'] : false;
        $medias = $this->Medias->find('all',array(
        	'conditions' => array('ref_id' => $ref_id, 'ref' => $ref)
        ))->all();
		
        $thumbID = false;
      
        if($this->$ref->hasField('media_id')){
        	$reference = $this->$ref->get($ref_id);
        	$thumbID = $reference->media_id;
        }
        $extensions = $this->$ref->medias['extensions'];
       	$editor = isset($this->request->query['editor']) ? $this->request->query['editor'] : false;
        $this->set(compact('id', 'medias', 'thumbID', 'editor', 'extensions'));
    }

    /**
    * Upload (Ajax)
    **/
    public function upload($ref, $ref_id)
    {
    	$this->layout = null;
        $this->autoRender = false;
        if(!$this->canUploadMedias($ref, $ref_id)){
            throw new ForbiddenException();
        }
        $media = $this->Medias->newEntity();
	
	if(isset($_FILES) && !empty($_FILES)) {
        	$data['ref'] = $ref;
	        $data['ref_id'] = $ref_id;
	        $data['file'] = $_FILES['file'];
	        $new_media = $this->Medias->patchEntity($media, $data);
	        
	       	$this->Medias->save($new_media, $_FILES);
	        if($this->Medias->error != ""){
	            echo json_encode(array('error' => $this->Medias->error));
	            return false;
	        }
	    }
        $this->loadModel($ref);
        $editor = isset($this->request->params['named']['editor']) ? $this->request->params['named']['editor'] : false;
        $id = isset($this->request->query['id']) ? $this->request->query['id'] : false;
        $this->set(compact('media', 'thumbID', 'editor', 'id'));
       
        $this->layout = 'json';
        $this->render('media');
    }

    /**
    * Suppression (Ajax)
    **/
    public function delete($id){
    	$this->layout = null;
        $this->autoRender = false;
        $media = $this->Medias->get($id);
        if(empty($media)){
            throw new NotFoundException();
        }
        if(!$this->canUploadMedias($media->ref, $media->ref_id)){
            throw new ForbiddenException();
        }
        $this->Medias->delete($media, ['atomic' => false]);
    }

    /**
    * Met l'image à la une
    **/
    public function thumb($id){
        $this->Medias->id = $id;
        $media = $this->Medias->get($id);
        if(empty($media)){
            throw new NotFoundException();
        }
        if(!$this->canUploadMedias($media->ref, $media['Media']['ref_id'])){
            throw new ForbiddenException();
        }
        $ref = $media->ref;
        $ref_id = $media->ref_id;
        $this->loadModel($ref);
        $table = $ref;
        $reference = $this->$table->get($ref_id);
        $reference->media_id = $id;
        $this->$table->save($reference);
        $this->redirect(array('action' => 'index', $table, $ref_id));
    }

    public function order(){
    	$this->layout = null;
    	$this->autoRender = false;
    	
    	if(!empty($this->request->data['Media'])){
            $id = key($this->request->data['Media']);
            
            $ref = $this->request->data['refName'];
            $reference = $this->$ref->get($id);
            if(!$this->canUploadMedias($reference->ref, $reference->ref_id)){
                throw new ForbiddenException();
            }
            foreach($this->request->data['Media'][$id] as $k => $v){
            	$media = $this->Medias->get($k); 
            	$media->position = $v;
               	$this->Medias->save($media);
            }
        }
    }
}

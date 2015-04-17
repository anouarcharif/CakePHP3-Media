<?php

namespace Media\Model\Behavior;

use Cake\ORM\Behavior;

class MediaBehavior extends Behavior {

	private $options = array(
		'path'    => 'img/uploads/%y/%m/%f',
		'extensions' => array('jpg', 'png', 'bmp'),
		'limit' => 0,
		'max_width' => 0,
		'max_height' => 0,
		'size' => 0, // in KB (Ko)
	);
	
	public $medias;
	
	
	public function initialize(array $config){
		
		$this->_table->medias =  array_merge($this->options, $config);
		$this->_table->hasMany('Media', [
            'className' => 'Media.Medias',
            'foreignKey' => 'ref_id',
			'order'		 => 'Media.position ASC',
			'conditions' => 'ref = "'.$this->_table->medias['refName'].'"',
			'dependent'  => true
        ]);
		if($this->_table->hasField('media_id')){
			$this->_table->belongsTo('thumb.Thumb', [
				'className'  => 'Media.Medias',
				'foreignKey' => 'media_id',
				'conditions' => null,
				'counterCache'=> false
			]);
			
		}
	}
	
	
	
	public function afterSave($event, $entity, $options = array()){
		if(!empty($this->request->data['thumb']['name'])){
			$file = $this->request->data['thumb'];

			// Current thumb
			$media_id = $entity->media_id;
			if($media_id != 0){
				$entity->Medias->delete($media_id);
			}
			$data = array(
				'ref_id' => $model->id,
				'ref'	 => $model->name,
				'file'   => $file
			);
			$media = $this->Medias->newEntity($media);
			$this->Medias->save($media);
			$entity->media_id = $this->Medias->id;
		}
	}

}

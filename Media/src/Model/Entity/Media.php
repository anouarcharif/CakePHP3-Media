<?php 

namespace Media\Model\Entity;

use Cake\ORM\Entity;

class Media extends Entity
{
	private $pictures = array('jpg','png','gif','bmp');
	private $icon, $type = null;
	public function initialize(array $config)
    {
     	 
    }
    
    public function icon() {
   			 if($this->file != ""){
				$pathinfo = pathinfo($this->file);
				$extension= $pathinfo['extension'];
				if(!in_array($extension, $this->pictures)){
					$this->icon = 'Media.' . $extension . '.png';
				}else{
					$this->icon = $this->file;
				}
			}
			return $this->icon;
    }
    
    public function type() {
   	 		if($this->file != ""){
				$pathinfo = pathinfo($this->file);
				$extension= $pathinfo['extension'];
				
				if(!in_array($extension, $this->pictures)){
					$this->type = $extension;
				}else{
					$this->type = 'pic';
				}
			}
			return $this->type;
    }
}

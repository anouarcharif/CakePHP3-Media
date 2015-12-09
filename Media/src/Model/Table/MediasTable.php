<?php 

// src/Model/Table/ArticlesTable.php



namespace Media\Model\Table;



use Cake\ORM\Table;

use Cake\ORM\TableRegistry;

use Cake\Utility\Inflector;

use Cake\Network\Exception\NotImplementedException;



class MediasTable extends Table

{

	

	public $error = "";

	function beforeFind($event, $query, $options, $primary){
		$query->order(['position ASC']);

	}
	
	function beforeSave($event, $entity, $options = array())
	{

		if($entity->ref != ""){

			$ref = $entity->ref;
			$table = TableRegistry::get($ref);

	        	if(!in_array('Media', $table->Behaviors()->loaded())){
				throw new NotImplementedException(__d('media',"La table '%s' n'a pas le comportement 'Media'", $ref));
			}

		}

		if( isset($options['file']) && is_array($options['file']) && $entity->ref != ""){

			$ref = $entity->ref;
			$table 	 = TableRegistry::get($ref);
			$ref_id 	= $entity->ref_id;

	        if(method_exists($ref, 'uploadMediasPath')){

	          $path = $this->$ref->uploadMediasPath($ref_id);

	        }else{

			  $path = $table->medias['path'];

	        }

	       

			$pathinfo 	= pathinfo($options['file']['name']);

			$extension  = strtolower($pathinfo['extension']) == 'jpeg' ? 'jpg' : strtolower($pathinfo['extension']);



			if(!in_array($extension, $table->medias['extensions'])){

				$this->error = __d('media','Vous ne pouvez pas uploader ce type de fichier ({%s} seulement)', implode(', ', $table->medias['extensions']));

				return false;

			}



			// Limit files count by ref/ref_id

			if ($table->medias['limit'] > 0 && $entity->ref_id > 0) {

				$qty = $this->find('all', array('conditions' => array('ref' => $entity->ref, 'ref_id' => $entity->ref_id)))->count();

				if ($qty >= $table->medias['limit']) {

					$this->error = __d('media', "Vous ne pouvez envoyer qu'un nombre limité de fichier (%d). Veuillez en supprimer avant d'en envoyer de nouveau.", $model->medias['limit']);

					return false;

				}

			}



			// Limit image size (for png/jpg/bmp/tiff)

			if (in_array($extension, array('jpg', 'png', 'bmp', 'tiff')) && ($table->medias['max_width'] > 0 || $table->medias['max_height'] > 0 )) {

				list($width,$height) = getimagesize($options['file']['tmp_name']);

				if ($table->medias['max_width'] > 0 && $width > $table->medias['max_width']) {

					$this->error = __d('media', "La largeur maximum autorisée est de %dpx", $table->medias['max_width']);

					return false;

				}

				if ($table->medias['max_height'] > 0 && $height > $table->medias['max_height']) {

					$this->error = __d('media', "La hauteur maximum autorisée est de %dpx", $model->medias['max_height']);

					return false;

				}

			}

			

			// Limit Image size

			if ($table->medias['size'] > 0 && floor($options['file']['size'] / 1024) > $table->medias['size']) {

				$humanSize		= $table->medias['size'] > 1024 ? round($table->medias['size']/1024,1).' Mo' : $table->medias['size'].' Ko';

				$this->error	= __d('media', "Vous ne pouvez pas envoyer un fichier supérieur à %s", $humanSize);

				return false;

			}



			if(method_exists($ref, 'uploadMediasPath')){

				$path = $this->$ref->uploadMediasPath($ref_id);

			}else{

				$path = $table->medias['path'];

			}



			$filename 	= Inflector::slug($pathinfo['filename'],'-');

			$search 	= array('/', '%id', '%mid', '%cid', '%y', '%m', '%f');

			$replace 	= array(DS, $ref_id, ceil($ref_id/1000), ceil($ref_id/100), date('Y'), date('m'), Inflector::slug($filename));

			$file  		= str_replace($search, $replace, $path) . '.' . $extension;

			$this->testDuplicate($file);

			if(!file_exists(dirname(WWW_ROOT.$file))){

				@mkdir(dirname(WWW_ROOT.$file),0777,true);

			}

			$this->move_uploaded_file($options['file']['tmp_name'], WWW_ROOT.$file);

			pl@chmod(WWW_ROOT.$file, 0777);

			$entity->file = '/' . trim(str_replace(DS, '/', $file), '/');

		}

		return true;

	}

	/**

	* If the file $dir already exists we add a {n} before the extension

	**/

	public function testDuplicate(&$dir,$count = 0){

		$file = $dir;

		if($count > 0){

			$pathinfo = pathinfo($dir);

			$file = $pathinfo['dirname'].'/'.$pathinfo['filename'].'-'.$count.'.'.$pathinfo['extension'];

		}

		if(!file_exists(WWW_ROOT.$file)){

			$dir = $file;

		}else{

			$count++;

			$this->testDuplicate($dir,$count);

		}

	}

	

	public function beforeDelete($event, $entity, $options){

		$file = $entity->file;

		$info = pathinfo($file);



		$resized = glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'_*x*.jpg');

		$original= glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'.'.$info['extension']);

		if(is_array($resized)){

			foreach($resized as $v){

				@unlink($v);

			}

		}

		if(is_array($original)){

			foreach($original as $v){

				@unlink($v);

			}

		}

		return true;

	}

	

	/**

	 * Aliast for the move_uploaded_file function, so it can be mocked for testing purpose

	 */

	public function move_uploaded_file($filename, $destination){

		return move_uploaded_file($filename, $destination);

	}

}

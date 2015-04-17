<?php
class MediaComponent extends Component{

    public function __construct($collection, $settings = array()){
        $this->controller = $collection->getController();
        parent::__construct($collection, $settings);
    }

    /**
     * Resize a file uploaded
     * @param  string   $path   Path of the data, for instance to upload the file in data['Movie']['picture'] path would be Movie.picture
     * @param  string   $dest   Where to save the uploaded file
     * @param  integer  $width
     * @param  integer  $height
     * @return boolean  True if the image is uploaded.
     */
    public function move($path, $dest, $width = 0, $height = 0){
        $file = Hash::get($this->controller->request->data, $path);
        if(empty($file['tmp_name'])){
            return false;
        }
        $tmp = TMP . $file['name'];
        move_uploaded_file($file['tmp_name'], $tmp);
        $info = pathinfo($file['name']);
        $destinfo = pathinfo($dest);
        $directory = dirname(IMAGES . $dest);
        if(!file_exists($directory)){
            mkdir($directory, 0777, true);
        }
        if($info['extension'] == $destinfo['extension'] && $width == 0){
            rename($tmp, IMAGES . $dest);
            return true;
        }
        if (!file_exists($dest_file)) {
            require_once APP . 'Plugin' . DS . 'Media' . DS . 'Vendor' . DS . 'imagine.phar';
            $imagine = new Imagine\Gd\Imagine();
            $imagine->open($tmp)->thumbnail(new Imagine\Image\Box($width, $height), Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND)->save(IMAGES . $dest, array('quality' => 90));
        }
        return true;
    }

}
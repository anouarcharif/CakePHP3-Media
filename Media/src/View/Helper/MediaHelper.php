<?php

namespace Media\View\Helper;

use Cake\View\Helper;

class MediaHelper extends Helper{

	public $helpers = array('Html','Form', 'Url');
	public $javascript = false;
	public $explorer = false;
	

	public function tinymce($field, $ref, $ref_id, $options = array()){
		$this->Html->script('/media/js/tinymce/tiny_mce.js', array('block' => 'script'));
		return $this->textarea($field, $ref, $ref_id, 'tinymce', $options);
	}

	public function ckeditor($field, $ref, $ref_id, $options = array()) {
		$this->Html->script('/media/js/ckeditor/ckeditor.js', array('block' => 'script'));
		return $this->textarea($field, $ref, $ref_id, 'ckeditor', $options);
	}

	public function redactor($field, $ref, $ref_id, $options = array()) {
		$this->Html->script('/media/js/redactor/redactor.min.js', array('block' => 'script'));
		$this->Html->css('/media/js/redactor/redactor.css', array('block' => 'css'));
		return $this->textarea($field, $ref, $ref_id, 'redactor', $options);
	}

	public function textarea($field, $ref, $ref_id, $editor = false, $options = array()){
		$options = array_merge(array('label'=>false,'style'=>'width:100%;height:300px','row' => 160, 'type' => 'textarea', 'class' => "wysiwyg $editor", 'id' => $editor), $options);
		$html = $this->Form->input($field, $options);
		if(isset($ref_id) && !$this->explorer){
			$html .= '<input type="hidden" id="explorer" value="'.$this->Url->build('/media/Medias/index/'.$ref.'/'.$ref_id).'">';
			$this->explorer = true;
    	}
    	return $html;
	}

	public function iframe($ref,$ref_id){
		return '<iframe src="' . $this->Url->build("/media/medias/index/$ref/$ref_id") . '" style="width:100%;" id="medias-' . $ref . '-' . $ref_id . '"></iframe>';
	}
}

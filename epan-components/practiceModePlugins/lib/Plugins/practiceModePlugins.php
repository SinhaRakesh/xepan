<?php

namespace practiceModePlugins;

class Plugins_practiceModePlugins extends \componentBase\Plugin{
	public $namespace = 'practiceModePlugins';

	function init(){
		parent::init();
		$this->addHook('epan-page-before-save',array($this,'saveToSessionOnly'));
		$this->addHook('output-fetched',array($this,'getFromSession'));
	}

	function saveToSessionOnly($obj,$current_page){
		$this->api->memorize('content_'. $current_page['id'] , $current_page['content']);
		$this->api->memorize('body_attributes_' . $current_page['id'] , $current_page['body_attributes']);
		throw $this->exception('','StopInit');
	}

	function getFromSession($obj,&$current_page){
		if($this->api->recall('content_' .$current_page['id'],false) !== false){
			$current_page['content'] = $this->api->recall('content_'. $current_page['id']);
			$current_page['body_attributes'] = $this->api->recall('body_attributes_'. $current_page['id']);
		}
	}

	function getDefaultParams($new_epan){}
}
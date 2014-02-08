<?php

namespace systemcontentmanipulationPlugins;

class Plugins_TemplateEditMode extends \componentBase\Plugin{
	public $namespace = 'systemcontentmanipulationPlugins';

	function init(){
		parent::init();
		$this->addHook('content-fetched',array($this,'outputFetched'));
	}

	function outputFetched($obj,&$page){
		if($this->api->edit_template){
			$page['content'] = '<div contenteditable="false" style="" class="epan-component" component_type="MainContent" component_namespace="templateRegions" id="main-content-div"> 	{{Content}} </div>';	
		}else{

		}	
	}

	function getDefaultParams($new_epan){}
}
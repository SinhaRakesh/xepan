<?php

namespace imageElement;

class View_Tools_SingleImage extends \componentBase\View_Component{
	function init(){
		parent::init();
		$this->js(true)->css('color','red');
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}
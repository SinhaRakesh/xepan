<?php

class page_menubarModule_page_install extends page_componentBase_page_install {
	
	function init(){
		parent::init();

		$this->install();
	}
}
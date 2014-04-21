<?php

class page_owner_frontapplauncher extends Page {
	function init(){
		parent::init();

		$this->add('View')->set('Not Working Now')->addClass('alert alert-info');
	}
}
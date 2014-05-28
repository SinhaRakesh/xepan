<?php

class page_developerZone_page_owner_component_edit extends page_developerZone_page_owner_main {
	function init(){
		parent::init();

		$this->api->stickyGET('component');

	}
}
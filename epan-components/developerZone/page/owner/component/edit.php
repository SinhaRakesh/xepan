<?php

class page_developerZone_page_owner_component_edit extends page_developerZone_page_owner_main {
	function init(){
		parent::init();
		$this->api->stickyGET('component');
		$this->add('H3')->setHTML('Editing <u>' . $_GET['component']. '</u> Component');

		$component = $this->add('Model_MarketPlace');
		$component->loadBy('namespace',$_GET['component']);


		$crud = $this->add('CRUD');
		$crud->setModel($component->ref('Tools'));

	}
}
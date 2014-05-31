<?php

class page_developerZone_page_owner_component_edit extends page_developerZone_page_owner_main {
	function init(){
		parent::init();

		$this->api->stickyGET('component');
		$this->add('H3')->setHTML('Editing <u>' . $_GET['component']. '</u> Component');

		$tabs = $this->add('Tabs');
		$tools_tab = $tabs->addTab('Tools');
		$plugins_tab = $tabs->addTab('Plugins');

		$component = $this->add('Model_MarketPlace');
		$component->loadBy('namespace',$_GET['component']);

		$crud = $tools_tab->add('CRUD');
		$crud->setModel($component->ref('Tools'));

		$crud2 = $plugins_tab->add('CRUD');
		$crud2->setModel($component->ref('Plugins'));

	}
}
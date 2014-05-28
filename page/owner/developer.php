<?php

class page_owner_developer extends page_base_owner {
	function init() {
		parent::init();
		$this->add( 'H1' )->setHTML( strtoupper($this->api->current_website['name']) . " Develoepr Zone <small>coding ... the easy way :) </small> " );

		$this->js(true)->univ()->developer_setup();

		if($_GET['page'] == 'owner_developer'){
			$grid = $this->add('Grid');
			$grid->setModel('MarketPlace',array('namespace','type','name','is_system','has_toolbar_tools','has_owner_modules','has_plugins','has_live_edit_app_page'));

			$btn=$grid->add('Button',null,'top_1')->set('New Component');
			$btn->js('click',$this->js()->univ()->redirect($this->api->url('owner_developer_new')));
		}
	}
}
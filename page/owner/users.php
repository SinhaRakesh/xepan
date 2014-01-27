<?php
class page_owner_users extends page_base_owner {
	function init(){
		parent::init();
		// $this->add('View_Info')->set($this->api->current_website['name']);
		$crud=$this->add('CRUD');
		$usr=$this->add('Model_Users');
		$usr->addCondition('epan_id',$this->api->current_website->id);
		$crud->setModel($usr);
	}
}
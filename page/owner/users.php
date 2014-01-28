<?php
class page_owner_users extends page_base_owner {
	function init(){
		parent::init();
		$this->add( 'H1' )->setHTML( "User Management <small>Manage your website / applications registered users</small>" );

		$crud=$this->add('CRUD');
		$usr=$this->add('Model_Users');
		$usr->addCondition('epan_id',$this->api->current_website->id);
		$crud->setModel($usr);
	}
}
<?php
class page_simplebillingApp_page_owner_party extends page_simplebillingApp_page_owner_main {
	function init(){
		parent::init();

		$this->add('H1')->set('Manage your Parties here');
		$this->add('HR');
		$crud=$this->add('CRUD');
		$party=$this->add('simplebillingApp/Model_Party');
		$party->addCondition('epan_id',$this->api->auth->model->id);
		$crud->setModel($party);
	}
}
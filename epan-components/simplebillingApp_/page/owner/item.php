<?php
class page_simplebillingApp_page_owner_item extends page_simplebillingApp_page_owner_main  {
	function init(){
		parent::init();

		$this->add('H1')->set('Manage your Product/Services Items');
		$this->add('HR');
		$crud=$this->add('CRUD');
		$item=$this->add('simplebillingApp/Model_Item');
		$item->addCondition('epan_id',$this->api->auth->model->id);
		$crud->setModel($item);

	}
}
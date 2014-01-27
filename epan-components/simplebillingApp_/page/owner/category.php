<?php
class page_simplebillingApp_page_owner_category extends page_simplebillingApp_page_owner_main  {
	function init(){
		parent::init();

		$this->add('H1')->set('Manage your Product/Services Categories');
		$this->add('HR');
		$crud=$this->add('CRUD');
		$category=$this->add('simplebillingApp/Model_Category');
		$category->addCondition('epan_id',$this->api->auth->model->id);
		$crud->setModel($category);
	}
}
<?php
class page_simplebillingApp_page_owner_salesbill extends page_simplebillingApp_page_owner_main{
	function init(){
		parent::init();
		
		$this->add('HR');
		$this->add('H2')->set('Sales Bill');
		$conf=$this->add('simplebillingApp/Model_Configuration');
		$conf->addCondition('epan_id',$this->api->auth->model->id);
		$conf->tryLoadAny();
		$this->add('simplebillingApp/View_Bill',array('type_of_bill'=>'Sales','no_of_items'=>$conf['bill_rows']));
	}
}
<?php
class page_simplebillingApp_page_owner_printbill extends page_componentBase_page_owner_main {
	function init(){
		parent::init();
		$this->api->stickyGET('bill_id');
		$this->h1->destroy();
		$this->toolbar->destroy();
		$bill_view = $this->add('simplebillingApp/View_PrintBill');
		$bill=$this->add('simplebillingApp/Model_Bill');
		$bill->load($_GET['bill_id']);
		$bill->addExpression('party_number')->set(function($m,$q){
			return $m->refSQL('simplebillingApp/party_id')->get('name');
		});

		$bill_view->setModel($bill);

		
	}
}
<?php
class page_simplebillingApp_page_owner_purchase_detail extends page_componentBase_page_owner_main {
	function init(){
		parent::init();

		$this->api->stickyGET('simplebillingApp_bill_id');
		$bill=$this->add('simplebillingApp/Model_Bill');
		$bill->load($_GET['simplebillingApp_bill_id']);
		$bill_detail=$bill->ref('simplebillingApp/BillDetail');
		$grid=$this->add('Grid');
		$grid->setModel($bill_detail,array('item','rate','qty','amount'));





	}


}
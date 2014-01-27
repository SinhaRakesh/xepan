<?php

class page_simplebillingApp_page_owner_receipt_paymentdetails extends page_componentBase_page_owner_main {
	function init(){
		parent::init();
		$this->api->stickyGET('simplebillingApp_bill_id');
		$bill=$this->add('simplebillingApp/Model_SalesBill')
				->load($_GET['simplebillingApp_bill_id']);

		
		$payment=$this->add('simplebillingApp/Model_PaymentReceived')->addCondition('bill_id',$bill->id);
		$payment->addCondition('epan_id',$this->api->auth->model->id);
		$crud=$this->add('CRUD');
		$crud->setModel($payment);
		if($crud->grid)
			$crud->js('reload',$crud->grid->js()->_selector('.bill_grid')->trigger('reload'));
	}
}
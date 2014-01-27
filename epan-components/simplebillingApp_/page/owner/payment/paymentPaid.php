<?php
class page_simplebillingApp_page_owner_payment_paymentPaid extends page_componentBase_page_owner_main {
	function init(){
		parent::init();

		$this->api->stickyGET('simplebillingApp_bill_id');
		$purchase_bill=$this->add('simplebillingApp/Model_PurchaseBill');
		$purchase_bill->load($_GET['simplebillingApp_bill_id']);
		$payment_paid=$purchase_bill->ref('simplebillingApp/PaymentPaid');
		$payment_paid->addCondition('epan_id',$this->api->auth->model->id);
		$crud=$this->add('CRUD');
		$crud->setModel($payment_paid);
		if($crud->grid)
			$crud->js('reload',$crud->grid->js()->_selector('.purchasebill_grid')->trigger('reload'));
	

	}
}
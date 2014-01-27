<?php
namespace simplebillingApp;
class Model_PaymentReceived extends Model_Transaction {
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/SalesBill','bill_id');
		$this->addCondition('transaction_type','Received');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
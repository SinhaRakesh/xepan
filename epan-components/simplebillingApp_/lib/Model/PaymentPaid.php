<?php
namespace simplebillingApp;
class Model_PaymentPaid extends Model_Transaction {
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/Bill','bill_id');
		$this->addCondition('transaction_type','Payment');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
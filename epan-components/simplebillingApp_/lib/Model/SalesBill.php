<?php
namespace simplebillingApp;
class Model_SalesBill extends Model_Bill {
	function init(){
		parent::init();
		$this->hasMany('simplebillingApp/PaymentReceived','bill_id');
		$this->addCondition('bill_type','Sales');
	}
}
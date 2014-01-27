<?php
namespace simplebillingApp;
class Model_PurchaseBill extends Model_Bill {
	function init(){
		parent::init();
		$this->hasMany('simplebillingApp/PaymentPaid','bill_id');
		$this->addCondition('bill_type','Purchase');
	}
}
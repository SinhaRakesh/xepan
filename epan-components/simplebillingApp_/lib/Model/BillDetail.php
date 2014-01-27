<?php
namespace simplebillingApp;
class Model_BillDetail extends \Model_Table {
	var $table= "simplebillingApp_billdetail";
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/Bill','bill_id');
		$this->hasOne('simplebillingApp/Item','item_id');
		$this->addField('qty');
		$this->addField('rate')->type('money');
		$this->addField('amount')->type('money');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}
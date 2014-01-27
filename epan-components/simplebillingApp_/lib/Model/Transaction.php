<?php
namespace simplebillingApp;
class Model_Transaction extends \Model_Table {
	var $table= "simplebillingApp_transaction";
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/Epan','epan_id');
		$this->addField('transaction_amount');
		$this->addField('transaction_date')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('transaction_updation_date')->type('date');
		$this->addField('transaction_mode')->enum(array('Cash','Cheque','DD','NET Banking','Other'));
		$this->addField('transaction_mode_remarks');
		$this->addField('transaction_remarks');
		$this->addField('transaction_type')->enum(array('Payment','Received'));

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}
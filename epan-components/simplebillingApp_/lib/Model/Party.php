<?php
namespace simplebillingApp;
class Model_Party extends \Model_Table {
	var $table= "simplebillingApp_party";
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/Epan','epan_id');
		$this->addField('name')->mandatory(true);
		$this->addField('address')->type('text');
		$this->addField('mobile_number')->type('int')->mandatory(true);
		$this->addField('phone_number')->type('int');
		$this->addField('email')->hint('Provide correct email to send invoice for email')->mandatory(true);
		$this->hasMany('simplebillingApp/Bills','bill_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
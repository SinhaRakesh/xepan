<?php
namespace simplebillingApp;

class Model_Item extends \Model_Table {
	var $table= "simplebillingApp_item";
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/Epan','epan_id');
		$this->hasOne('simplebillingApp/Category','category_id')->mandatory(true);
		$this->addField('name')->caption('Product/Services')->mandatory(true);
		$this->addField('rate')->type('money');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}
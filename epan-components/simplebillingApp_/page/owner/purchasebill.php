<?php
class page_simplebillingApp_page_owner_purchasebill extends page_simplebillingApp_page_owner_main{
	function init(){
		parent::init();

		$this->add('HR');
		$this->add('H2')->set('Purchase Bill');
		$this->add('simplebillingApp/View_Bill',array('type_of_bill'=>'Purchase'));
	}
}
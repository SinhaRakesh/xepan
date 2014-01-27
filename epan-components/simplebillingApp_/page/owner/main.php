<?php

class page_simplebillingApp_page_owner_main extends page_componentBase_page_owner_main {
	function init(){
		parent::init();

		$menu=$this->add('Menu');
		$menu->addMenuItem('simplebillingApp_page_owner_category','Category');
		$menu->addMenuItem('simplebillingApp_page_owner_item','Products / Services');
		$menu->addMenuItem('simplebillingApp_page_owner_party','Party');
		$menu->addMenuItem('simplebillingApp_page_owner_sales','Sales');
		$menu->addMenuItem('simplebillingApp_page_owner_receipt','Receipt');
		$menu->addMenuItem('simplebillingApp_page_owner_purchase','Purchase');
		$menu->addMenuItem('simplebillingApp_page_owner_payment','Payment');
		$menu->addMenuItem('simplebillingApp_page_owner_report','Report');
		$menu->addMenuItem('simplebillingApp_page_owner_config','Configruration');
		
	}
}
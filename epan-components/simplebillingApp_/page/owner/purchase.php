<?php
class page_simplebillingApp_page_owner_purchase extends page_simplebillingApp_page_owner_main  {
	function page_index(){
		// parent::init();

		$this->add('H1')->set('Manage your Purchase Bills');
		$purchase_btn=$this->add('Button')->set('Genrate Purchase Bill');
		$purchase_btn->js('click',$this->js()->univ()->redirect($this->api->url('simplebillingApp_page_owner_purchasebill')));
		$this->add('HR');

		$grid=$this->add('Grid');
		$purchase=$this->add('simplebillingApp/Model_PurchaseBill');
		$purchase->addCondition('epan_id',$this->api->auth->model->id);
		$grid->setModel($purchase);
		$grid->addColumn('button','edit');
		$grid->addColumn('expander','detail');
		if($_GET['edit']){
			$this->api->redirect($this->api->url('simplebillingApp_page_owner_purchasebill',array('bill_id'=>$_GET['edit'])));
		}
	}
}
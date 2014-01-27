<?php
class page_simplebillingApp_page_owner_payment extends page_simplebillingApp_page_owner_main{
	function page_index(){
		

		$this->add('H1')->set('Manage your Payable Bills');
		$this->add('HR');
		$grid=$this->add('Grid');
		$purchase=$this->add('simplebillingApp/Model_PurchaseBill');
		$purchase->addCondition('epan_id',$this->api->auth->model->id);
		$purchase->addExpression('paid_amount')->set(function($m,$q){
			return $m->refSQL('simplebillingApp/PaymentPaid')->sum('transaction_amount');
		});
		$grid->setModel($purchase);
		$grid->addMethod('format_dueAmount',function($g,$field){
			$g->current_row[$field]=$g->current_row['net_amount']-$g->current_row['paid_amount'];
		});

		$grid->addColumn('dueAmount,money','due_amount');
		$grid->addColumn('expander','paymentPaid');
		$grid->addPaginator(10);
		$grid->addClass('purchasebill_grid');
		$grid->js('reload')->reload();
	
	}
}
<?php
class page_simplebillingApp_page_owner_receipt extends page_simplebillingApp_page_owner_main  {
	function page_index(){
		// parent::init();

		$this->add('H1')->setHTML('Manage your Payment Received<small>Against Raised Sales Invoices</small>');
		$this->add('HR');
		// $form=$this->add('Form');
		// $party_field=$form->addField('autocomplete/Basic','party_name');
		// $party_field->setModel('simplebillingApp/Party');
		// $form->addField('line','amount');
		// $form->addSubmit('Go');

		$grid=$this->add('Grid');
		$bill_model=$this->add('simplebillingApp/Model_SalesBill');
		$bill_model->addCondition('epan_id',$this->api->auth->model->id);

		$bill_model->addExpression('paid_amount')->set(function($m,$q){
			return $m->refSQL('simplebillingApp/PaymentReceived')->sum('transaction_amount');
		});

		$grid->addMethod('format_dueAmount',function($g,$field){
			$g->current_row[$field]=$g->current_row['net_amount']-$g->current_row['paid_amount'];
		});
		$grid->setModel($bill_model);
		$grid->addColumn('dueAmount,money','due_amount');
		$grid->addColumn('expander','paymentdetails','Receives');
		$grid->addPaginator(10);
		$grid->addClass('bill_grid');
		$grid->js('reload')->reload();
	}

	// function page_paymentdetail(){
	// 	throw new \Exception("Error Processing Request", 1);
		

	 	
	// }
}
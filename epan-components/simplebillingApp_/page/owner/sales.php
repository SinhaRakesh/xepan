<?php
class page_simplebillingApp_page_owner_sales extends page_simplebillingApp_page_owner_main{
	function page_index(){
		// parent::init();

		$this->add('H1')->set('Manage your Sales Bills');
		$this->add('HR');
		$new_sales_btn=$this->add('Button')->set('Generate Bill');
		$grid=$this->add('Grid');
		$sales=$this->add('simplebillingApp/Model_SalesBill');
		$sales->addCondition('epan_id',$this->api->auth->model->id);
		$sales->getElement('epan')->system(true);
		$sales->getElement('remark')->system(true);
		$grid->setModel($sales);
		$grid->addPaginator(20);
		$grid->addColumn('button','edit');
		$grid->addColumn('expander','detail');
		$grid->addColumn('button','print');

		$grid->addMethod('format_email',function($g,$field){
			if($g->model->ref('party_id')->get('email') == '')
				$g->current_row_html[$field] = '';
		});

		$grid->addColumn('button,email','email');
		if($_GET['edit']){
			$this->api->redirect($this->api->url('simplebillingApp_page_owner_salesbill',array('bill_id'=>$_GET['edit'])));
		}
		if($_GET['print']){
			$this->js()->univ()->newWindow($this->api->url('simplebillingApp_page_owner_printbill',array('bill_id'=>$_GET['print'],'cut_page'=>1)))->execute();
		}

		if($_GET['email']){
			$email_bill = $this->add('simplebillingApp/Model_Bill')->load($_GET['email']);
			$email_bill->sendToParty();
			$this->js()->univ()->successMessage('Invoice Send to Party')->execute();
		}

		$new_sales_btn->js('click',$this->js()->univ()->redirect($this->api->url('simplebillingApp_page_owner_salesbill')));
		
	}
}
<?php
class page_simplebillingApp_page_owner_report extends page_simplebillingApp_page_owner_main  {
	function init(){
		parent::init();
		$this->add('H1')->set('Search & Report Section');
		$this->add('HR');
		
		$tabs = $this->add('Tabs');
		$bill_tab = $tabs->addTab('Bill Reports');
		$payment_tab = $tabs->addTab('Payment Reports');

		// BILL REPORT SECTION
		$bill_search_form = $bill_tab->add('Form',null,null,array('form_horizontal'));
		$bill_search_form->addClass('stacked');
		$bill_search_form->addField('DatePicker','from_date');
		$bill_search_form->addField('DatePicker','to_date');
		$my_bill_party = $this->add('simplebillingApp/Model_Party');
		$my_bill_party->addCondition('epan_id',$this->api->auth->model->id);
		$bill_search_form->addField('autocomplete/Basic','party')->setModel($my_bill_party);
		$bill_search_form->addField('dropdown','bill_type')->setValueList(array('Sales'=>'Sales','Purchase'=>'Purchase'));
		$bill_search_form->addSubmit('Search');

		$grid = $bill_tab->add('Grid');

		$bills = $this->add('simplebillingApp/Model_Bill');
		$bills->addCondition('epan_id',$this->api->auth->model->id);

		if($_GET['filter_bill']){
			$this->api->stickyGet('filter');
			if($_GET['from_date']){
				$this->api->stickyGet('from_date');
				$bills->addCondition('bill_date','>=' , $_GET['from_date']);
			}
			if($_GET['to_date']){
				$this->api->stickyGet('to_date');
				$bills->addCondition('bill_date','<=' , $_GET['to_date']);	
			}
			if($_GET['party']){
				$this->api->stickyGet('party');
				$bills->addCondition('party_id',$_GET['party']);
			}
			if($_GET['bill_type']){
				$this->api->stickyGet('bill_type');
				$bills->addCondition('bill_type',$_GET['bill_type']);
			}
		}

		$grid->setModel($bills);
		$grid->addPaginator(20);

		if($bill_search_form->isSubmitted()){
			$grid->js()->reload(array(
					'filter_bill'=>1,
					'from_date'=>$bill_search_form['from_date']?:0,
					'to_date' => $bill_search_form['to_date']?:0,
					'party' => $bill_search_form['party'],
					'bill_type' => $bill_search_form['bill_type']
				))->execute();
		}

		// PAYMENT REPORT SECTION

		$payment_search_form = $payment_tab->add('Form',null,null,array('form_horizontal'));
		$payment_search_form->addClass('stacked');
		$payment_search_form->addField('DatePicker','from_date');
		$payment_search_form->addField('DatePicker','to_date');
		$my_payment_party = $this->add('simplebillingApp/Model_Party');
		$my_payment_party->addCondition('epan_id',$this->api->auth->model->id);
		$payment_search_form->addField('autocomplete/Basic','party')->setModel($my_payment_party);

		$payment_search_form->addField('dropdown','payment_type')->setValueList(array('Payment'=>'Payment','Received'=>'Received'));
		$payment_search_form->addSubmit('Search');

		$payment_grid = $payment_tab->add('Grid');

		$payments = $this->add('simplebillingApp/Model_Transaction');
		$payments->addCondition('epan_id',$this->api->auth->model->id);
		$payments->addExpression('party')->set(function($m,$q){
			$p_m = $m->add('simplebillingApp/Model_Transaction');
			$p_m->table_alias = '_t';
			$j=$p_m->leftJoin('simplebillingApp_bill','bill_id')->leftJoin('simplebillingApp_party','party_id');
			$j->addField('party_name','name');
			$p_m->addCondition('id',$q->getField('id'));

			return $p_m->fieldQuery('party_name');
		});
		$payments->addExpression('party_id')->set(function($m,$q){
			$p_m = $m->add('simplebillingApp/Model_Transaction');
			$p_m->table_alias = '_tid';
			$j=$p_m->leftJoin('simplebillingApp_bill','bill_id')->leftJoin('simplebillingApp_party','party_id');
			$j->addField('party_id','id');
			$p_m->addCondition('id',$q->getField('id'));

			return $p_m->fieldQuery('party_id');
		});


		if($_GET['filter_payment']){
			$this->api->stickyGET('filter_payment');
			if($_GET['from_date']){
				$this->api->stickyGET('from_date');
				$payments->addCondition('transaction_date','>=',$_GET['from_date']);
			}
			if($_GET['to_date']){
				$this->api->stickyGET('to_date');
				$payments->addCondition('transaction_date','<=',$_GET['to_date']);	
			}
			if($_GET['party']){
				$this->api->stickyGET('party');
				$payments->addCondition('party_id',$_GET['party']);
			}

			if($_GET['payment_type']){
				$this->api->stickyGET('payment_type');
				$payments->addCondition('transaction_type',$_GET['payment_type']);	
			}
		}


		$payment_grid->setModel($payments);
		$payment_grid->addPaginator(20);

		if($payment_search_form->isSubmitted()){
			$payment_grid->js()->reload(array(
					'filter_payment'=>1,
					'from_date'=>$payment_search_form['from_date']?:0,
					'to_date' => $payment_search_form['to_date']?:0,
					'party' => $payment_search_form['party'],
					'payment_type' => $payment_search_form['payment_type']
				))->execute();
		}


	}
}
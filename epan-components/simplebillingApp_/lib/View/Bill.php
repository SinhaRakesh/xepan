<?php
namespace simplebillingApp;

class View_Bill extends \View{
	public $no_of_items=null;
	public $type_of_bill=null;
	function init(){
		parent::init();
		$this->api->stickyGET('bill_id');
		if($this->type_of_bill == null)
			 throw new \Exception("Error Processing Request", 1);
			 
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css',
		  		'js'=>'templates/js'
				)
			)->setParent($l);

		$this->api->jquery->addStylesheet('simplebillingApp-default');		
		// $this->api->jquery->addStaticInclude('simplebillingApp-script');
		$this->api->template->appendHTML('js_include','<script src="/epan-addons/simplebillingApp/templates/js/script.js"></script>');

		$bill=$this->add('simplebillingApp/Model_Bill');
		if($_GET['bill_id'])
			$bill->load($_GET['bill_id']);
		$form=$this->add('Form');
		$form->addClass('stacked');

		$form->addSeparator('atk-row');
		$date_field = $form->addField( 'DatePicker','bill_date');
		$date_field->template->set('row_class','span7');
		$date_field->set(date('Y-m-d'));
		$party_field = $form->addField( 'autocomplete/Basic','party_name');
		$party_field->validateNotNull();
		$party_field->set($bill['party_id']);
		$party_field->setModel( 'simplebillingApp/Party' );
		$form->addField('line','bill_no')->set($bill['bill_no']);
		$cols= $form->add('Columns');
		$left=$cols->addColumn(6);
		$right=$cols->addColumn(6);
		// $left->add($form->getElement('bill_date'));
		$left->add($form->getElement('party_name')->other_field);
		$right->add($form->getElement('bill_no'));
		

		$amount_fields_array = array();

		if($_GET['bill_id']){
			$this->no_of_items=$bill->ref('simplebillingApp/BillDetail')->count()->getOne()+2;
			$bill_details_array=$bill->ref('simplebillingApp/BillDetail')->getRows();
		}


		for($i=1;$i<=$this->no_of_items;$i++){
			$s_no_label='S No.';
			$item_label='Item/Services';
			$rate_label='Rate';
			$qty_label='Quantity';
			$amount_label='Amount';
			if($i!=1){
				$s_no_label=" ";
				$item_label=" ";
				$rate_label=" ";
				$qty_label=" ";
				$amount_label=" ";
			}

			$form->addSeparator('atk-row noborder');
			$form->addField('Readonly','s_no_'.$i,$s_no_label)->set($i)->template->set('row_class','span1');

			$item_field=$form->addField('autocomplete/Basic','items_'.$i,$item_label);
			$item_field->other_field->template->set('row_class','span5');
			$item_field->setModel('simplebillingApp/Item');

			$rate_field=$form->addField('line','rate_'.$i,$rate_label);
			$rate_field->template->set('row_class','span2');

			$quantity_field=$form->addField('line','qty_'.$i,$qty_label);
			$quantity_field->template->set('row_class','span2');
		
			$amount_field=$form->addField('line','amount_'.$i,$amount_label);
			$amount_field->template->set('row_class','span2');
			$amount_field->setAttr('disabled','disabled');

			$amount_fields_array[] = $amount_field;

		}


		$form->addSeparator('atk-row');
		$gross_total_field=$form->addField('line','gross_total');
		$gross_total_field->template->set('row_class','span2 offset10');
		$gross_total_field->setAttr('disabled','disabled');
		$form->addSeparator('atk-row');
		$discount_remark_field=$form->addField('line','discount_remark');
		$discount_remark_field->template->set('row_class','span2 offset7');
		$discount_field=$form->addField('line','discount');
		$discount_field->template->set('row_class','span2');
		$discount_field->afterField()->add('View')->addClass('btn btn-default btn-xs')->set('Rs/-');

		$form->addSeparator('atk-row');
		$total_field=$form->addField('line','total');
		$total_field->template->set('row_class','span2 offset10');
		$total_field->setAttr('disabled','disabled');

		$form->addSeparator('atk-row');
		$tax_field=$form->addField('dropdown','tax');


		$config=$this->add('simplebillingApp/Model_Configuration');
		$config->addCondition('epan_id',$this->api->auth->model->id);
		$config->tryLoadAny();
		if($config->loaded()){
			$tax_dropdown=array();
			$all_taxes_array=explode(';',$config['applicable_taxes']);
			foreach ($all_taxes_array as $tax) {
				$tax_array=explode('-',$tax);
				$tax_dropdown += array($tax_array[0]=>$tax_array[1]);
			}
		}
		$tax_field->setValueList($tax_dropdown);
		$tax_field->template->set('row_class','span2 offset7');
		// $form->addSeparator('atk-row');
		$tax_amount=$form->addField('line','tax_amount');
		$tax_amount->template->set('row_class','span2');
		$tax_amount->setAttr('disabled','disabled');

		$form->addSeparator('atk-row');
		$net_amount_field=$form->addField('line','net_amount');
		$net_amount_field->template->set('row_class','span2 offset10');
		$net_amount_field->setAttr('disabled','disabled');

		$remark_field=$form->addField('text','remark');
		$form->addSubmit('Save');
		
		// Implement Js 
		for($i=1;$i <= $this->no_of_items;$i++){
			$rate_field=$form->getElement('rate_'.$i);
			$amount_field = $form->getElement('amount_'.$i);
			$quantity_field = $form->getElement('qty_'.$i);

			$rate_field->js('change')->univ()
				->calculateRow($rate_field,$quantity_field,$amount_field)
				->calculateGrossTotal($amount_fields_array,$gross_total_field)
				->calculateTotal($gross_total_field,$discount_field,$total_field)
				->calculateTax($total_field,$tax_field,$tax_amount)
				->calculateNetAmount($total_field,$tax_amount,$net_amount_field)
				;
			$quantity_field->js('change')->univ()
				->calculateRow($rate_field,$quantity_field,$amount_field)
				->calculateGrossTotal($amount_fields_array,$gross_total_field)
				->calculateTotal($gross_total_field,$discount_field,$total_field)
				->calculateTax($total_field,$tax_field,$tax_amount)
				->calculateNetAmount($total_field,$tax_amount,$net_amount_field)
				;
		}

		$discount_field->js('change')->univ()
				->calculateGrossTotal($amount_fields_array,$gross_total_field)
				->calculateTotal($gross_total_field,$discount_field,$total_field)
				->calculateTax($total_field,$tax_field,$tax_amount)
				->calculateNetAmount($total_field,$tax_amount,$net_amount_field)
				;
		$tax_field->js('change')->univ()
				->calculateGrossTotal($amount_fields_array,$gross_total_field)
				->calculateTotal($gross_total_field,$discount_field,$total_field)
				->calculateTax($total_field,$tax_field,$tax_amount)
				->calculateNetAmount($total_field,$tax_amount,$net_amount_field)
				;


		
		if($_GET['bill_id']){
			for ($i=1; $i <=$this->no_of_items ; $i++) { 
				$form->getElement('items_'.$i)->set($bill_details_array[$i-1]['item_id']);
				$form->getElement('rate_'.$i)->set($bill_details_array[$i-1]['rate']);
				$form->getElement('qty_'.$i)->set($bill_details_array[$i-1]['qty']);
				$form->getElement('amount_'.$i)->set($bill_details_array[$i-1]['amount']);
				$form->getElement('bill_date')->set($bill['bill_date']);
			}
				$form->getElement('gross_total')->set($bill['gross_total']);
				$form->getElement('discount_remark')->set($bill['discount_remark']);
				$form->getElement('discount')->set($bill['discount_amount']);
				$form->getElement('total')->set($bill['total']);
				$form->getElement('tax')->set($bill['tax_detail']);
				$form->getElement('tax_amount')->set($bill['tax_amount']);
				$form->getElement('net_amount')->set($bill['net_amount']);
				$form->getElement('remark')->set($bill['remark']);

		}

		if($form->isSubmitted()){
			
			$bill['epan_id']=$this->api->auth->model->id;
			$bill['party_id']=$form['party_name'];
			$bill['bill_no']=$form['bill_no'];
			$bill['bill_date']=$form['bill_date'];
			$bill['tax_detail']=$form->get('tax');
			$bill['discount_remark']=$form->get('discount_remark');
			$bill['discount_amount']=$form->get('discount');
			$bill['remark']=$form->get('remark');
			$bill['bill_type']=$this->type_of_bill;
			
			$bill->save();
			
			$bill->ref('simplebillingApp/BillDetail')->deleteAll();
			
			$_gross_total = 0;
			for($i=1;$i<=$this->no_of_items;$i++){
				if($form['items_'.$i] == null ) continue;
				$bill_details=$this->add('simplebillingApp/Model_BillDetail');
				
				$bill_details['bill_id']=$bill->id;
				$bill_details['item_id']=$form['items_'.$i];
				$bill_details['qty']=$form['qty_'.$i];
				$bill_details['rate']=$form['rate_'.$i];
				$_gross_total += ($bill_details['amount']=$form['qty_'.$i] * $form['rate_'.$i]);
				$bill_details->save();
			}

			$bill['gross_total'] = $_gross_total;
			$bill['total'] = $_gross_total - $bill['discount_amount'] ;
			$bill['tax_amount'] = $bill['total'] * $form['tax_detail'] / 100.00 ;
			$bill['net_amount'] = $bill['total'] + $bill['tax_amount'];
			$bill->save();
			
			
			$conf=$this->add('simplebillingApp/Model_Configuration');
			$conf->addCondition('epan_id',$this->api->auth->model->id);
			$conf->tryLoadAny();
			$js=array();
			
			if($conf['send_email_on_invoicing']){
				if($bill->ref('party_id')->get('email')!=''){
					// $bill->sendToParty();
					$js[]=$form->js()->univ()->successMessage("Email Send To Party");
					$js[]=$form->js()->univ()->newWindow($this->api->url('simplebillingApp_page_owner_printbill',array('bill_id'=>$bill->id,'cut_page'=>1)));

				}
					
			}
						
			$js[] = $form->js()->univ()->successMessage("Bill Saved");
			$js[]=$form->js()->reload();
			
			$form->js(null,$js)->execute();

		}

	}
}
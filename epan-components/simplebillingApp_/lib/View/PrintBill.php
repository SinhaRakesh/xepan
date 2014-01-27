<?php
namespace simplebillingApp;
class View_PrintBill extends \View{

	function setModel($model){
		parent::setModel($model);
		$con=$this->add('simplebillingApp/Model_Configuration');
		$con->addCondition('epan_id',$this->api->auth->model->id);
		$con->tryLoadAny();
		$this->template->trySet('company_logo',$con['company_logo']);
		$this->template->trySetHTML('company_name',$con['company_name']);
		$this->template->trySetHTML('company_address',$con['company_address']);
		$this->template->trySetHTML('company_terms',$con['company_terms']);
		$this->template->trySet('party_number',$model->ref('party_id')->get('mobile_number'));
		$this->template->trySet('party_address',$model->ref('party_id')->get('address'));
		
		$bill_detail=$this->add('simplebillingApp/View_BillDetail',null,'bill_detail');
		$bill_detail->setModel($model->ref('simplebillingApp/BillDetail'));
		

	}
	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/simplebillingApp-printbill');

	}
}
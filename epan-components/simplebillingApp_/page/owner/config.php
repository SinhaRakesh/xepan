<?php
class page_simplebillingApp_page_owner_config extends page_simplebillingApp_page_owner_main  {
	function init(){
		parent::init();

		$this->add('H1')->set('Update your Billing Software here.');
		$form=$this->add('Form');
		$config=$this->add('simplebillingApp/Model_Configuration');
		$config->addCondition('epan_id',$this->api->auth->model->id);
		$config->tryLoadAny();
		$form->setModel($config);
		$form->addSubmit('Update');

		
		if ($form->isSubmitted()){

			$form->update();
			$form->js(null,$form->js()->reload())->univ()->successMessage('Configuration updated!')->execute();


		}



	}
}
<?php

class page_referrernotifierPlugins_page_owner_main extends page_componentBase_page_owner_main {
	function init(){
		parent::init();

		$this->add('HR');//->setHTML('Notification Configutation');
		$this->add('H3')->setHTML('Notification Configutation');

		$config = $this->add('referrernotifierPlugins/Model_Configuration');
		$config->addCondition('epan_id',$this->api->auth->model->id);
		$config->tryLoadAny();

		$form = $this->add('Form');
		$form->setModel($config);

		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$form->update();
			$form->js()->univ()->successMessage('Configuration Updated')->execute();
		}

	}
}
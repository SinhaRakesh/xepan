<?php

class page_owner_developer_new extends page_owner_developer {
	function init(){
		parent::init();

		$app = $this->add('Model_MarketPlace');
		$app->getElement('type')->enum(array('module','application'));

		$form = $this->add('Form');
		$form->setModel($app,array('namespace','type','name','has_toolbar_tools','has_owner_modules','has_plugins','has_live_edit_app_page'));

		$form->addSubmit('Verify And Create');

		if($form->isSubmitted()){
			$form->update();
			$form->js()->univ()->reload();
		}

	}
}
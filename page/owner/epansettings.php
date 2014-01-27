<?php

class page_owner_epansettings extends page_base_owner {
	public $tabs;
	function page_index(){
		// parent::init();

		$this->tabs = $tabs = $this->add('Tabs');
		$epan_info = $tabs->addTab('Information');
		// TODO delete from single Web based CMS
		$this->add('Controller_EpanCMSApp')->addAliasesPage();
		
		$credentials_tab = $tabs->addTab('Your Credentials');

		$epan_info_form = $epan_info->add('Form');
		$epan_info_form->setModel($this->api->current_website,array('category_id','company_name','contact_person_name','mobile_no','email_id','address','city','state','country','keywords','description'));
		$epan_info_form->addSubmit('Update');
		if($epan_info_form->isSubmitted()){
			$epan_info_form->update();
			$epan_info_form->js()->univ()->successMessage('Information Updated')->execute();
		}


		
		
		$credential_form = $credentials_tab->add('Form');
		
		$credential_form->addField('Readonly','username')->set($this->api->auth->model['name']);
		$credential_form->addField('password','current_password');
		$credential_form->addField('password','new_password');
		$credential_form->addField('password','retype_new_password');
		$credential_form->addSubmit('Update');

		if($credential_form->isSubmitted()){
			$epan = $this->add('Model_Epan')->load($this->api->auth->model->id);
			if($epan['password'] != $credential_form['current_password'])
				$credential_form->displayError('current_password','Current password is not correct');

			if($credential_form['new_password'] != $credential_form['retype_new_password'])
				$credential_form->displayError('retype_new_password','Passwords do not match');

			$epan['password'] = $credential_form['new_password'];
			$epan->save();
			$credential_form->js(null,$credential_form->js()->reload())->univ()->successMessage('Password changed successfully')->execute();
		}

	}
}
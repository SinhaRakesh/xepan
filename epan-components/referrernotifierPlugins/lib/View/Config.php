<?php

namespace referrernotifierPlugins;


class View_Config extends \View{
	public $form;
	function init(){
		parent::init();
		$epan_plugin_config = $this->add('Model_InstalledComponents');
		$epan_plugin_config->addCondition('epan_id',$this->api->auth->model->id);
		$epan_plugin_config->addCondition('component_id',$_GET['epan_components_marketplace_id']);
		$epan_plugin_config->tryLoadAny();

		$params=json_decode($epan_plugin_config['params']);

		$this->form = $this->add('Form');
		
		$this->form->addField('checkbox','enable')->set($epan_plugin_config['enabled']);
		$this->form->addField('line','email_to')->set(@$params->email_to);
		$this->form->addSubmit('Update');

		if($this->form->isSubmitted()){
			$epan_plugin_config['enabled'] = $this->form['enable'];

			$params=array('email_to'=>$this->form['email_to']);
			$epan_plugin_config['params']=json_encode($params);
			$epan_plugin_config->save();
			$this->form->js(null,$this->form->js()->univ()->successMessage('Config Updated'))->univ()->closeExpander()->execute();
		}
	}
}
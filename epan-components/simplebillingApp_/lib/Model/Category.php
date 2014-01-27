<?php
namespace simplebillingApp;

class Model_Category extends \Model_Table {
	var $table= "simplebillingApp_category";
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/Epan','epan_id');
		$this->addField('name')->mandatory(true);
		$this->hasMany('simplebillingApp/Item','category_id');
		$this->addHook('beforeDelete',$this);
		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this->ref('simplebillingApp/Item')->count()->getOne()>0)
			$this->api->js()->univ()->errorMessage("Cannot Delete, It contain Items!!!")->execute();			
	}

	function beforeSave(){
		$old_model=$this->add('simplebillingApp/Model_Category');
		if($this->loaded()){
			$old_model->addCondition('id','<>',$this->id);
		}

		$old_model->addCondition('name',$this['name']);
		$old_model->tryLoadAny();
		if($old_model->loaded()){

			// throw new Exception("Error Processing Request", 1);
			$this->api->js()->univ()->errorMessage("This category is Allready Exist, Take Another !!!")->execute();			
		}
			
		
	}
}
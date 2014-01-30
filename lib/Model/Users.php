<?php
class Model_Users extends Model_Table {
	var $table= "users";
	function init(){
		parent::init();
		$this->hasOne('Epan','epan_id')->mandatory(true);
		$this->addField('name');
		$this->addField('username');
		$this->addField('password');
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d'));
		// $this->addField('is_systemuser')->type('boolean')->defaultValue(false);
		// $this->addField('is_frontenduser')->type('boolean')->defaultValue(false);
		// $this->addField('is_backenduser')->type('boolean')->defaultValue(false);
		$this->addField('type')->enum(array('SuperUser','FrontEndUser','BackEndUser'))->defaultValue('FrontEndUser');

		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this['username'] == $this->ref('epan_id')->get('name'))
			throw $this->exception("You Can't delete it, it is default username");
			
	}

}
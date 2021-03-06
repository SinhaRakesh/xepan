<?php

class Model_EpanTemplates extends Model_Table {
	var $table= "epan_templates";
	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addField('name');
		$this->addField('content')->type('text')->defaultValue('{{Content}}');
		$this->addField('css')->type('text');
		$this->addField('is_current')->type('boolean')->defaultValue(false);
		$this->hasMany('EpanPage','template_id');
		

		$this->addCondition('epan_id',$this->api->current_website->id);


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
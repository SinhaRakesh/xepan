<?php

class Model_Tools extends Model_Table {
	var $table= "epan_components_tools";
	function init(){
		parent::init();

		$this->hasOne('MarketPlace','component_id');
		
		$this->addField('name');
		
		$this->addField('is_serverside')->type('boolean');
		$this->addField('is_sortable')->type('boolean');
		$this->addField('is_resizable')->type('boolean');


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
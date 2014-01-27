<?php

namespace referrernotifierPlugins;

class Model_Configuration extends \Model_Table {
	var $table= "epan_searchengine_refral_configuration";
	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id')->sortable(true);
		$this->addField('Email_on_each_visit')->type('boolean')->defaultValue(false);
		$this->addField('Email_on_each_searchengine_click')->type('boolean')->defaultValue(true);

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
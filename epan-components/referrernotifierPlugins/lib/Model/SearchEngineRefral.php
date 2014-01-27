<?php

namespace referrernotifierPlugins;

class Model_SearchEngineRefral extends \Model_Table {
	var $table= "epan_searchengine_refral";
	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id')->sortable(true);
		$this->addField('search_engine_url')->sortable(true);
		$this->addField('keywords')->type('text')->sortable(true);
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d H:i:s'))->sortable(true);
		$this->addField('is_mail_sent')->type('boolean')->defaultValue(false);
	}
}
<?php

namespace pageversionsPlugins;

class Model_PageVersionGoals extends \Model_Table {
	var $table= "pageversionsPlugins_competitor_goals";
	function init(){
		parent::init();

		$this->hasOne('pageversionsPlugins/CompetitorPages','compete_id');
		$this->addField('name')->caption('Goal');
		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
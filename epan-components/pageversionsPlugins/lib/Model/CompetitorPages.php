<?php

namespace pageversionsPlugins;


class Model_CompetitorPages extends \Model_Table {
	var $table= "pageversionsPlugins_competitor_pages";
	function init(){
		parent::init();

		$this->hasOne('EpanPage','epanpage_id');
		$this->hasOne('EpanPageSnapshots','snapshot_id');
		$this->addField('last_displayed_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));

		$this->hasMany('pageversionsPlugins/PageVersionGoals','compete_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
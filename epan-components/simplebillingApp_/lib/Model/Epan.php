<?php

namespace simplebillingApp;


class Model_Epan extends \Model_Epan {
	function init(){
		parent::init();

		$this->hasMany('simplebillingApp/Category','epan_id');
		$this->hasMany('simplebillingApp/Item','epan_id');
		$this->hasMany('simplebillingApp/Party','epan_id');
		$this->hasMany('simplebillingApp/Configuration','epan_id');

	}
}
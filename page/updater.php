<?php

class page_updater extends Page {
	function init(){
		parent::init();

		$this->query("UPDATE epan_page SET content=REPLACE(content,'/epans/','epans/')");
		// TODO : Marketplace plugin_hooked not required now
		// TODO : replace in content /epan-addons/ with /epan-components
		// ie 
			// 1. social share script urlCURL
		// 
	}

	function query($q){
		$this->api->db->dsql($this->api->db->dsql()->expr($q))->execute();
	}
}
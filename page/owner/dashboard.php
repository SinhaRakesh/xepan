<?php

class page_owner_dashboard extends page_base_owner {
	function init() {
		parent::init();
		$this->add( 'View_Info' )->set( $this->api->current_website['name'] );
	}
}
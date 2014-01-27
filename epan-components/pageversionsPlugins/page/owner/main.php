<?php

class page_pageversionsPlugins_page_owner_main extends page_componentBase_page_owner_main {
	function init(){
		parent::init();

		$tabs = $this->add('Tabs');
		$version_tab = $tabs->addTabURL('pageversionsPlugins_page_owner_setversions','Set Versions');

	}
}
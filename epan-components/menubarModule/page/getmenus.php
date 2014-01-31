<?php

class page_menubarModule_page_getmenus extends Page {
	function init(){
		parent::init();

		$menus = "";

		foreach ($this->api->current_website->ref('EpanPage')->addCondition('menu_caption','<>',"") as $pages) {
			if($this->api->getConfig('sef_url')){
				$menus .= '<li><a href="/'.$pages['name'].'">'.$pages['menu_caption'].'</a></li>';
			}else{
				$menus .= '<li><a href="?epan='.$this->api->current_website['name']. '&subpage=' .$pages['name'].'">'.$pages['menu_caption'].'</a></li>';
			}
		}

		echo $menus;
		exit;

	}
}
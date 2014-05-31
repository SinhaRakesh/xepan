<?php

class page_developerZone_page_owner_main extends page_componentBase_page_owner_main {

	function init(){
		parent::init();
		if(!$this->api->isAjaxOutput()){
			$this->api->template->appendHTML('js_include','<script src="epan-components/developerZone/templates/js/developerZone-base.js"></script>'."\n");
			$this->menu->template->tryDel('side_menu');
			$this->api->template->tryDel('wrapper');
			$this->toolbar->addButton( 'Developer Home' )->js( 'click', $this->js()->univ()->redirect( $this->api->url('developerZone_page_owner_main') ) );
			$this->toolbar->addButton( 'Dashboard' )->js( 'click', $this->js()->univ()->redirect( $this->api->url('owner_dashboard') ) );
		}
	}

	function page_index(){
		if($_GET['page'] == 'developerZone_page_owner_main'){
			$this->add('H4')->set('Available Components in Local MarketPlace');
			$grid = $this->add('Grid');
			$grid->setModel('MarketPlace',array('namespace','type','name','is_system','has_toolbar_tools','has_owner_modules','has_plugins','has_live_edit_app_page'));

			$btn=$grid->add('Button',null,'top_1')->set('New Component');
			$btn->js('click',$this->js()->univ()->redirect($this->api->url('developerZone_page_owner_component_new')));

			$grid->setFormatter('namespace','template')->setTemplate('<a href="?page=developerZone_page_owner_component_edit&component=<?$namespace?>"><?$namespace?></a>');

			$grid->addColumn('Button','download');

			if($_GET['download']){
				$component = $this->add('Model_MarketPlace');
				$component->load($_GET['download']);
				$component->download();
				$this->js()->univ()->successMessage('ok')->execute();
			}

		}
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}
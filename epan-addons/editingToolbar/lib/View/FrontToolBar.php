<?php

namespace editingToolbar;
/**
 * This is frontend tool bar class render it self as well as tools
 */

class View_FrontToolBar extends \View{
	function init(){
		parent::init();
		
		// Get All Elements 
		$block_tabs = $this->add('Tabs',null,'tools');

		// $tool_block = $this->add('View',null,'tools')->addClass('block-title');
		// $tool_block->add('View')->set('Elements');
		$market_place = $this->add('Model_MarketPlace')->addCondition('type','element');
		
		$element_tab =$block_tabs->addTab('Elements');

		foreach ($market_place as $market_place_array) {
			foreach ($tools = $market_place->ref('Tools') as $cmp) {
				$element_tab->add('editingToolbar/View_Tool',array('namespace'=>$market_place['namespace'],'title'=>$tools['name'],'class'=>'View_Tools_'.str_replace("_", "", $this->api->normalizeName($tools['name'])),'is_serverside'=>$tools['is_serverside'],'is_sortable'=>$tools['is_sortable'],'is_resizable'=>$tools['is_resizable']));
			}
		}

		if($this->api->edit_template){
			$element_tab->add('templateRegions/View_Tools_MainContentRegion');
			// $this->add('templateRegions/View_Tools_EditableContentRegion',null,'tools');
		}

		// Get All INSTALLED Modules and Applications
		$installed_components  = $this->api->current_website->ref('InstalledComponents');
		// TODO DELETE FOLLOWING LINE
		// $componenet_j = $installed_components->join('epan_components_marketplace','component_id');
		$installed_components->addCondition('has_toolbar_tools',true);

		foreach ($installed_components as $junk) {
			// TODO DELETE FOLLOWING LINE
			// $cmp = $installed_components->ref('component_id');
			$component_tab = $block_tabs->addTab($installed_components['name']);
			// $tool_block = $this->add('View',null,'tools')->addClass('block-title');
			// $tool_block->add('View')->set($installed_components['name']);
			foreach ($installed_components as $market_place_array) {
				foreach ($tools = $installed_components->ref('Tools') as $cmp) {
					$component_tab->add('editingToolbar/View_Tool',array('namespace'=>$installed_components['namespace'],'title'=>$tools['name'],'class'=>'View_Tools_'.str_replace("_", "", $this->api->normalizeName($tools['name'])),'is_serverside'=>$tools['is_serverside'],'is_sortable'=>$tools['is_sortable'],'is_resizable'=>$tools['is_resizable']));
				}
			}
		}

		$this->add('componentBase/View_CssOptions',null,'common_css_options')->js(true)->hide();
		$this->template->trySet('website_requested',$this->api->website_requested);
		$this->template->trySet('current_website_id',$this->api->current_website->id);
		$this->template->trySet('current_page_name',$this->api->current_page['name']);
		$this->template->trySet('current_page_id',$this->api->current_page->id);
		$this->template->trySet('current_template_id',$_GET['edit_template']?:$this->api->current_page['template_id']);
	}

	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/editingToolbar-toolbar');
	}
} 
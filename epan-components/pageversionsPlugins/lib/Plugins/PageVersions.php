<?php

namespace pageversionsPlugins;

class Plugins_PageVersions extends \componentBase\Plugin{
	public $namespace = 'pageversionsPlugins';

	function init(){
		parent::init();
		$this->addHook('output-fetched',array($this,'pageSelected'));
		$this->addHook('goal-achieved',array($this,'goalAchieved'));		
	}

	function pageSelected($obj,$selected_page){
		
		if($this->api->edit_mode) return;

		$selected_page->hasMany('pageversionsPlugins/CompetitorPages','epanpage_id');
		if($selected_page->ref('pageversionsPlugins/CompetitorPages','epanpage_id')->count()->getOne() >= 2){
			$last_visited_snapshot = $this->add('pageversionsPlugins/Model_CompetitorPages');
			$last_visited_snapshot->addCondition('epanpage_id',$selected_page->id);
			$last_visited_snapshot->setOrder('last_displayed_at');
			$last_visited_snapshot->tryLoadAny();

			// update content 
			$selected_page['content']=$last_visited_snapshot->ref('snapshot_id')->get('content');
			$selected_page['body_attributes']=$last_visited_snapshot->ref('snapshot_id')->get('body_attributes');
			$selected_page['title']=$last_visited_snapshot->ref('snapshot_id')->get('title');
			$selected_page['description']=$last_visited_snapshot->ref('snapshot_id')->get('description');
			$selected_page['keywords']=$last_visited_snapshot->ref('snapshot_id')->get('keywords');
			
			$last_visited_snapshot['last_displayed_at'] = date('Y-m-d H:i:s');
			$last_visited_snapshot->save();

			$this->api->memorize('version_for_page_'.$selected_page->id, $last_visited_snapshot->id);

			// Set page_version id in session
			// set last display dat to nows
		}
			
	}

	function goalAchieved($obj,$goals){
		
		if($this->api->edit_mode) return;
		
		$last_visited_snapshot = $this->add('pageversionsPlugins/Model_CompetitorPages');
		$last_visited_snapshot->addCondition('id',$this->api->recall('version_for_page_'.$this->api->current_page->id));
		$last_visited_snapshot->tryLoadAny();

		if(!$last_visited_snapshot->loaded()) return;

		$goal = $this->add('pageversionsPlugins/Model_PageVersionGoals');
		$goal['compete_id'] = $last_visited_snapshot->id;
		$goal['name'] = $goals;

		$goal->save();

	}

	function getDefaultParams($new_epan){}
}
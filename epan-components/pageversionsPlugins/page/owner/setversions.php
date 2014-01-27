<?php

class page_pageversionsPlugins_page_owner_setversions extends page_base_owner {
	
	function page_index(){

		$this->add('View')->set('Page versions performs actions only on snapshots. In case of No Snapshot or Single Snap shot No choice will be made')->addClass('alert alert-info');

		$grid = $this->add('Grid');
		$grid->setModel($this->api->auth->model->ref('EpanPage')->addCondition('menu_caption','<>',''),array('name','menu_caption','title'));
		$grid->addColumn('Expander','snapshots');
	}

	function page_snapshots(){
		$this->api->stickyGET('epan_page_id');
		$epan_page = $this->add('Model_EpanPage')->load($_GET['epan_page_id']);
		

		$grid = $this->add('Grid');
		$snapshots = $epan_page->ref('EpanPageSnapshots');
		$snapshots->hasMany('pageversionsPlugins/CompetitorPages','snapshot_id');


		$snapshots->addExpression('is_competing')->set(function($m,$q){
			return $m->refSQL('pageversionsPlugins/CompetitorPages')->count();
		})->setValueList(array('N','Y'));



		if($epan_page->ref('EpanPageSnapshots')->count()->getOne() >=2) {
			$grid->addColumn('Button','compete');
		}

		if($epan_page->ref('EpanPageSnapshots')->sum('is_competing')->getOne() <= 1){
			$grid->add('View',null,'top_1')->set('There must be at least two spanshots competing, currently only Main Page will be shown')->addClass('alert alert-danger');
		}

		$grid->setModel($snapshots,array('name','updated_on','title','is_competing'));
		if($_GET['compete']){
			$snapshot = $this->add('Model_EpanPageSnapshots')->load($_GET['compete']);
			$new_compete = $this->add('pageversionsPlugins/Model_CompetitorPages');
			$new_compete->addCondition('snapshot_id',$_GET['compete']); 
			$new_compete->addCondition('epanpage_id', $snapshot['epan_page_id']);
			$new_compete->tryLoadAny();
			if(!$new_compete->loaded()){
				$new_compete['last_displayed_at'] = date('Y-m-d H:i:s');
				$new_compete->save();
				$grid->js()->reload()->execute();
			}else{
				$new_compete->delete();
				$grid->js()->reload()->execute();
			}
		}
	}
}
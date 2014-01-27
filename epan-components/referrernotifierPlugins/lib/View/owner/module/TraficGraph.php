<?php

namespace referrernotifierPlugins;

class View_owner_module_TraficGraph extends \componentBase\View_owner_module{
	function init(){
		parent::init();
        /*
		DATA FORMAT
		array(
			[0] => Array
	        (
	            [name] => MAHI
	            [data] => Array
	                (
	                    [0] => 0
	                    [1] => 0
	                    [2] => 0
	                )

	        )
       )

       xAXIS FORMAT
       Array
		(
		    [0] => 2013-11-20
		    [1] => 2013-11-21
		    [2] => 2013-11-22
		)

		*/

		$ch=$this->add('chart/Chart');
		$data=array();
		$xaxis=array();

		$visit_data =$this->api->db->dsql()->table('epan_searchengine_refral')
								->field('count(*) visits')
								->field('date(created_at) date')
								->group('date(created_at)')
								->order('created_at')
								->where('epan_id',$this->api->auth->model->id);


		$xaxis = $visit_data->getAll();
		$xaxis_data=array();
		foreach ($xaxis as $junk) {
			$xaxis_data[] = $junk['date'];
			$data[] = (int)$junk['visits'];
		}

		$data_array=array(array('name'=>'Daily','data'=>$data));

		$ch
		->setXAxisTitle("Date")
		->setXAxis($xaxis_data)
		->setYAxisTitle("Visits")
		->setTitle("Daily Visits (Beta)",null,"Your Website visits (Rough estimation as its a beta module right now)")
		->setChartType('line')
		->setLegendsOptions(array("layout"=>"vertical","align"=>"right","verticalAlign"=>"top"));
		$ch->options['series']=$data_array;//array(array('name'=>'daily','data'=>array(1,2,3,2,1)));

	}
}
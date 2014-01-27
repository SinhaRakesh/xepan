<?php
namespace simplebillingApp;
class View_BillDetail extends \CompleteLister{
	public $i=1;
	function formatRow(){
		$this->current_row['sno'] = $this->i++;
	}

	function setModel($model){
		parent::setModel($model);
		$conf=$this->add('simplebillingApp/Model_Configuration');
		$conf->addCondition('epan_id',$this->api->auth->model->id);
		$conf->tryLoadAny();
		$extra_sno = $extrarows =  $conf['bill_rows']- $model->count()->getOne();
		for ($i=1; $i<=$extrarows; $i++){
			$v=$this->add('View',null,'ExtraRows',array('view/simplebillingApp-extrarows'));
			$v->template->trySet('sno',($extrarows-$extra_sno)+$i+2);
		}
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
		return array('view/simplebillingApp-billdetail');

	}
}
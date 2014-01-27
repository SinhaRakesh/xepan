<?php

namespace referrernotifierPlugins;

class Plugins_ReferrerNotifier extends \componentBase\Plugin{
	public $namespace = 'referrernotifierPlugins';

	function init(){
		parent::init();
		$this->addHook('epan-hit',array($this,'epanHit'));
	}

	function epanHit($obj,$epan){

		if(strpos($_SERVER['HTTP_HOST'],'localhost') !==false)
			return;

		$config = $this->add('referrernotifierPlugins/Model_Configuration');
		$config->addCondition('epan_id',$this->api->auth->model->id);
		$config->tryLoadAny();

		$result=array('keywords'=>array(),'searchengineurl'=>'');
		$send_email = 'N';

		if($_SERVER['HTTP_REFERER'] AND false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])){
			// Search Engine Referrar			
			$result = $this->add('referrernotifierPlugins/Referrer')->ExtractKeywords($_SERVER['HTTP_REFERER']);
			if($result['searchengineurl'] == '' OR !isset($result['searchengineurl']))
				$result['searchengineurl'] = $result['searchengine'] ;
			$send_email = 'Y';
			if(trim($this->api->current_epan['email_id'])=='') $send_email='N';
			if($config['Email_on_each_searchengine_click'] == 0) $send_email = 'N';
			if($result['keywords'] ==null) $result['keywords'] = array();
		}else{
			// Direct Opening
			$send_email = 'Y';
			if(trim($this->api->current_epan['email_id'])=='') $send_email = 'N';
			if($config['Email_on_each_visit'] == 0) $send_email = 'N';
			$result['searchengineurl'] = $_SERVER['HTTP_HOST'];
			$result['keywords'] = array();
		}

		$send_msg_js=null;
		if($send_email=='Y'){
			$send_msg_js = $this->api->js()->univ()->errorMessage('Contacting Owner');
		}

		$this->api->js(true,$send_msg_js)
		->univ()
		->ajaxec($this->api->url('referrernotifierPlugins_page_sendNotification',array('referrer'=>$_SERVER['HTTP_REFERER'],'searchengineurl'=>$result['searchengineurl'],'keywords'=>implode(' ',$result['keywords']),'send_email'=>$send_email)));
	}


	function getDefaultParams($new_epan){
		$params=array('email_to'=>$new_epan['email_id']);
		 return json_encode($params);
	}
}
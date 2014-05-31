<?php

namespace baseElements;


class Plugins_RunServerSideComponent extends \componentBase\Plugin {

	function init(){
		parent::init();
		$this->addHook('content-fetched',array($this,'Plugins_RunServerSideComponent'));
	}

	function Plugins_RunServerSideComponent($obj, $page){
		include_once (getcwd().'/lib/phpQuery/phpQuery/phpQuery.php');
		$doc = \phpQuery::newDocument($page['content']);
		
		$server = $doc['[data-is-serverside-component=true]'];
		foreach($doc['[data-is-serverside-component=true]'] as $ssc){

			$namespace =  pq($ssc)->attr('data-responsible-namespace');
			$view =  pq($ssc)->attr('data-responsible-view');
			if(!file_exists(getcwd().DS.'epan-components'.DS.$namespace.DS.'View'.DS.'Tools'.DS.$view.'.php'))
				$temp_view = $this->add('View_Error')->set("Server Side Component Not Found :: $namespace/$view");
			else
				$temp_view = $this->add("$namespace/$view",array('options'=>pq($ssc)->attr('data-options')));
			if(!$_GET['cut_object'] and !$_GET['cut_page']){
				$html = $temp_view->getHTML();
				pq($ssc)->html("")->append($html);
			}
		}
		$page['content'] = $doc->htmlOuter();
	}
}

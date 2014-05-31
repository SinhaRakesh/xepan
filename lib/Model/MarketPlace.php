<?php

class Model_MarketPlace extends Model_Table {
	var $table= "epan_components_marketplace";
	public $isInstalling=false;	
	
	function init(){
		parent::init();
		
		$this->addField('namespace')->hint('Variable style unique name')->mandatory();
		$this->addField('type')->enum(array('element','module','application','plugin'));
		$this->addField('name');
		// $this->addField('is_final')->type('boolean')->defaultValue(false);
		// $this->addField('rate')->type('number');
		$this->addField('allowed_children')->hint('comma separated ids of allowed children, mark final for none, and \'all\' for all');
		$this->addField('specific_to')->hint('comma separated ids of specified parent ids only, leave blank for none, and \'body\' for root only');

		$this->addField('is_system')->type('boolean')->defaultValue(false)->hint('System compoenets are not available to user for installation');
		$this->addField('description')->type('text')->display(array('grid'=>'text'));
		// $this->addField('plugin_hooked')->type('text');
		$this->addField('default_enabled')->type('boolean')->defaultValue(true);
		$this->addField('has_toolbar_tools')->type('boolean')->defaultValue(false)->caption('Tools');
		$this->addField('has_owner_modules')->type('boolean')->defaultValue(false)->caption('Owner Module');
		$this->addField('has_plugins')->type('boolean')->defaultValue(false)->caption('Plugins');
		$this->addField('has_live_edit_app_page')->type('boolean')->defaultValue(false)->caption('Has Front App');

		$this->hasMany('InstalledComponents','component_id');
		$this->hasMany('Tools','component_id');
		$this->hasMany('Plugins','component_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if(!$this['type']) throw $this->exception('Please specify type', 'ValidityCheck')->setField('type');

		$existing_check = $this->add('Model_MarketPlace');
		$existing_check->tryLoadBy('namespace',$this['namespace']);
		if($existing_check->loaded())
			throw $this->exception('Name Space Already Used', 'ValidityCheck')->setField('namespace');

		// TODO :: check namespace on server as well...
		if(file_exists(getcwd().DS.'epan-components'.DS.$this['namespace'])){
			throw $this->exception('namespace folder is already created', 'ValidityCheck')->setField('namespace');
		}

		if(!$this->isInstalling) //Added in AddComponentTorepository View
			$this->createNewFiles();

	}

	function createNewFiles(){
		$source=getcwd().DS.'epan-addons'.DS.'componentStructure'.DS.'namespace';
		$dest=getcwd().DS.'epan-components'.DS.$this['namespace'];

		$this->api->xcopy($source,$dest);

		foreach (
		  $iterator = new RecursiveIteratorIterator(
		  new RecursiveDirectoryIterator($dest, RecursiveDirectoryIterator::SKIP_DOTS),
		  RecursiveIteratorIterator::SELF_FIRST) as $item) {
		  if ($item->isDir()) {
		  	continue;
		  } else {
		  	// open file $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName()
		  	// replace {namespace} with $this['namespace']
		  	// save
		  	$file =$item;
			$file_contents = file_get_contents($file);
			$fh = fopen($file, "w");
			$file_contents = str_replace('{namespace}',$this['namespace'],$file_contents);
			fwrite($fh, $file_contents);
			fclose($fh);
		  }
		}
	}

	function beforeDelete(){
		if(!($deleted = $this->api->rrmdir($path = getcwd().DS.'epan-components'.DS.$this['namespace']))){
		}
		// throw $this->exception($path . ' deleted = '. $deleted, 'ValidityCheck')->setField('FieldName');
	}

	function createPackage(){
		// Make Db Backup (xml config file ) file

		$xml = new SimpleXMLElement('<xml/>');

		$xml->addChild('name',$this['name']);
		$xml->addChild('namespace',$this['namespace']);
		$xml->addChild('type',$this['type']);
		$xml->addChild('allowed_children',$this['allowed_children']?:0);
		$xml->addChild('specific_to',$this['specific_to']?:0);
		$xml->addChild('is_system',$this['is_system']);
		$xml->addChild('description',$this['description']?:0);
		$xml->addChild('default_enabled',$this['default_enabled']);
		$xml->addChild('has_toolbar_tools',$this['has_toolbar_tools']);
		$xml->addChild('has_owner_modules',$this['has_owner_modules']);
		$xml->addChild('has_live_edit_app_page',$this['has_live_edit_app_page']);

		$tools_node = $xml->addChild('Tools');

		foreach ($tools = $this->ref('Tools') as $tools_array) {
			$tool_node = $tools_node->addChild('Tool');
			$tool_node->addChild('name',$tools['name']);
			$tool_node->addChild('is_serverside',$tools['is_serverside']);
			$tool_node->addChild('is_resizable',$tools['is_resizable']);
			$tool_node->addChild('is_sortable',$tools['is_sortable']);

		}

		$plugins_node = $xml->addChild('Plugins');

		foreach ($plugins = $this->ref('Plugins') as $plugin_array) {
			$plugin_node = $plugins_node->addChild('Plugin');
			$plugin_node->addChild('name',$plugins['name']);
			$plugin_node->addChild('event',$plugins['event']);
			$plugin_node->addChild('params',$plugins['params']);
			$plugin_node->addChild('is_system',$plugins['is_system']);
		}

		file_put_contents(getcwd().DS.'epan-components'.DS.$this['namespace'].DS.'config.xml', $xml->asXML());
		// Zip file
		$component_zip = new zip;
		$component_zip->makeZip(getcwd().DS.'epan-components'.DS.$this['namespace'].DS.'/.',getcwd().DS.'epan-components'.DS.$this['namespace'].DS.$this['namespace'].'.zip');
		// Download file
		// delete created zip file
	}

}
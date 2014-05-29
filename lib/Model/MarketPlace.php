<?php

class Model_MarketPlace extends Model_Table {
	var $table= "epan_components_marketplace";
	
	function init(){
		parent::init();
		
		$this->addField('namespace')->hint('Variable style unique name')->mandatory();
		$this->addField('type')->enum(array('element','module','application','plugin'));
		$this->addField('name');
		$this->addField('is_final')->type('boolean')->defaultValue(false);
		$this->addField('rate')->type('number');
		$this->addField('allowed_children')->hint('comma separated ids of allowed children, mark final for none, and \'all\' for all');
		$this->addField('specific_to')->hint('comma separated ids of specified parent ids only, leave blank for none, and \'body\' for root only');

		$this->addField('is_system')->type('boolean')->defaultValue(false)->hint('System compoenets are not available to user for installation');
		$this->addField('description')->type('text')->display(array('grid'=>'text'));
		$this->addField('plugin_hooked')->type('text');
		$this->addField('default_enabled')->type('boolean')->defaultValue(true);
		$this->addField('has_toolbar_tools')->type('boolean')->defaultValue(false);
		$this->addField('has_owner_modules')->type('boolean')->defaultValue(false);
		$this->addField('has_plugins')->type('boolean')->defaultValue(false);
		$this->addField('has_live_edit_app_page')->type('boolean')->defaultValue(false);

		$this->hasMany('Tools','component_id');

		$this->addHook('beforeSave',$this);

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
			throw $this->exception('namespace is already in use', 'ValidityCheck')->setField('namespace');
		}
		
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


}
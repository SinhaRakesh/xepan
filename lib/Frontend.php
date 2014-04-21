<?php

class Frontend extends ApiFrontend{
	/**
	 * stores currrent website model object
	 *
	 * @var Model_Epan
	 */
	public $current_website=null;
	/**
	 * Stores current page about to render
	 *
	 * @var Model_EpanPage
	 */
	public $current_page=null;
	/**
	 * Stores Website requested from GET[config['website_request_variable']]
	 *
	 * @var String
	 */
	public $website_requested=null;
	/**
	 * Stores Page requested from GET[config['page_request_variable']]
	 *
	 * @var String
	 */
	public $page_requested=null;
	/**
	 * Handels CMS mode,
	 * If set to true all editing features will be enabled in frontend side
	 *
	 * @var boolean
	 */
	public $edit_mode=false;

	// TODO Comments
	public $edit_template = false;

	/**
	 * $epan_plugins contains all plugins associated with this website/epan
	 * loaded in frontend
	 *
	 * @var array
	 */
	public $website_plugins=array();

	function init() {
		parent::init();

		// A lot of the functionality in Agile Toolkit requires jUI

		if ( !file_exists('config-default.php') ) {
			// Not installed and installation required
			// TODO : check security issues
			$config['url_postfix']='';
			$config['url_prefix']='?page=';

			$this->setConfig($config);
			$this->add( 'jUI' );

			// A lot of the functionality in Agile Toolkit requires jUI
			$this->js()
			->_load( 'atk4_univ' )
			->_load( 'ui.atk4_notify' )
			;
			
			$this->page = 'install';
		}else {
			// already installed connect to provided settings and go on
			$this->dbConnect();



			$this->requires( 'atk', '4.2.0' );


			$this->addLocation( 'templates', array( 'css'=>'default/css' ) );

			$this->addLocation( '.', array(
					'addons'=>array( 'epan-addons', 'epan-components', 'xavoc-addons' ) )
			);

			// This will add Epan Market Place Location
			$this->addLocation( 'epan-addons', array(
					'page'=>array( "." ),
				) );
			// This will add Epan Market Place Location
			$this->addLocation( 'epan-components', array(
					'page'=>array( "." ),
				) );
			// This will add some resources from atk4-addons, which would be located
			// in atk4-addons subdirectory.
			$this->addLocation( 'atk4-addons', array(
					'php'=>array(
						'mvc',
						'misc/lib',
					)
				) )
			->setParent( $this->pathfinder->base_location );

			/**
			 * TODO: wrap in a IF(page does not contains owner_ / branch_ / system_ )
			 * only then you need to get all this, as you are looking front of a website
			 * -----------------------------------------------------------------------
			 * Get the request from browser query string and set various Api variables like
			 * current_website, current_page, website_requested and page_requested
			 * Once set that can be accessed all CMS vise like
			 * $this->api->current_website
			 */
			if ( true /*page does not contain owner_ / branch_ or system_ */ ) {
				$site_parameter= $this->getConfig( 'url_site_parameter' );
				$page_parameter= $this->getConfig( 'url_page_parameter' );

				$this->stickyGET( $site_parameter );
				$this->stickyGET( $page_parameter );

				$this->website_requested = $this->getConfig( 'default_site' );
				/**
				 * $this->page_requested finds and gets the requested page
				 * Always required in both multi site mode and single site mode
				 *
				 * @var String
				 */
				$this->page_requested=trim( $_GET[$page_parameter], '/' )
					?  trim( $_GET[$page_parameter], '/' )
					: $this->getConfig( 'default_page' );

				if ( $this->isAjaxOutput() or $_GET['cut_page'] ) {
					// set page_requested to referrer page not the page requested by
					// ajax request
					$this->add( 'Controller_AjaxRequest' );

				}


				$this->current_website = $this->add( 'Model_Epan' )->tryLoadBy( 'name', $this->website_requested );
				if ( $this->current_website->loaded() ) {
					$this->current_page = $this->current_website->ref( 'EpanPage' )
					->addCondition( 'name', $this->page_requested )
					->tryLoadAny();
				}else {
					$this->exec_plugins( 'error404', $this->website_requested );
				}

				// MULTISITE CONTROLER
				// $this->load_plugins();
				// $this->add( 'Controller_EpanCMSApp' )->frontEnd();
				// if ( $this->current_website->loaded() )
				// 	$this->exec_plugins( 'website-loaded', $this->api->current_website );
				// if ( $this->current_page->loaded() )
				// 	$this->exec_plugins( 'website-page-loaded', $this->api->page_requested );

			}
			$auth=$this->add( 'BasicAuth' );
			$auth->setModel( 'Users', 'username', 'password' );

			if($this->api->auth->isLoggedIn() AND $this->api->auth->model->ref('epan_id')->get('name')==$this->api->website_requested AND ($this->api->auth->model['type'] == 'SuperUser' OR $this->api->auth->model['type'] == 'BackEndUser')){
				$this->edit_mode = true;
			}

			if($_GET['edit_template']){
				$this->edit_template = true;
				$this->api->template->appendHTML('js_include','\nsfjdhkj;\n');
				$this->stickyGET('edit_template');
			}

			$this->load_plugins();

			$this->add( 'jUI' );
			// Global Template Setting
			if(in_array('shared', $this->defaultTemplate())){
				if($this->edit_template){
					$current_template = $this->add('Model_EpanTemplates')->load($_GET['edit_template']);
				}else{
					$current_template = $this->current_page->ref('template_id');
				}

				if($current_template->loaded()){
					if(!$this->edit_template){
						// Remove contenteditable from template strings
						// In General Page View Mode
						$this->api->exec_plugins('content-fetched',$current_template);
						
					}

					$shared_template = file_get_contents('templates/default/shared.html');
					/*$content .= '<?$Content?>';*/
					if(!$this->edit_template){
						include_once (getcwd().'/lib/phpQuery/phpQuery/phpQuery.php');
						$doc = \phpQuery::newDocument($current_template['content']);
						
						$content_divs = $doc['div:contains("{{Content}}")'];
						$i=0;
						foreach($content_divs as $temp){
							$i++;
						}

						if($i==0){
							$current_template['content'] .= "{{Content}}";
						}


						$current_template['content'] = str_replace("{{Content}}", '<?$Content?>', $current_template['content']);
						$shared_template = str_replace('<?$Content?>', $current_template['content'], $shared_template);						
					}else{
						$shared_template = str_replace('<?$Content?>', $current_template['content'], $shared_template);
						$shared_template .= '<?$Content?>';
					}

					// Saving since serverside components have been run already 
					// as plugin and they may have set some js_include ect in shared
					// But now shared template is about to load from string and 
					// old includes etc will be lost so ...
					$old_jui = $this->api->jui;
					$old_js_include = $this->template->tags['js_include'];
					// throw new Exception(print_r($old_js_include,true) , 1);
					
					$this->template->loadTemplateFromString($shared_template);
					$this->template->appendHTML('js_include',implode("\n", $old_js_include[0]));
					$this->template->trySet('template_css',$current_template['css']);
					
				}
				
			}

			$this->api->jui  = $old_jui;
			// unset($this->api->jui);
			// $this->add( 'jUI' );

			$this->add( 'Controller_EpanCMSApp' )->frontEnd();
			if ( $this->current_website->loaded() )
				$this->exec_plugins( 'website-loaded', $this->api->current_website );
			if ( $this->current_page->loaded() )
				$this->exec_plugins( 'website-page-loaded', $this->api->page_requested );

			// A lot of the functionality in Agile Toolkit requires jUI
			$this->js()
			->_load( 'atk4_univ' )
			->_load( 'ui.atk4_notify' )
			;

			if(in_array("shared", $this->defaultTemplate())){
				$this->template->appendHTML('js_include','<link type="text/css" href="templates/default/css/epan.css" rel="stylesheet" />'."\n");
			}
		}
	}

	function load_plugins() {

		$this->website_plugins_array=array();
		$this->website_plugins=array();

		$plugins = $this->add( 'Model_InstalledComponents' )
		->addCondition( 'epan_id', $this->api->current_website->id )
		->addCondition( 'has_plugins', true );
		$marketplace_j = $plugins->join( 'epan_components_marketplace', 'component_id' );

		foreach ( $plugins->getRows() as $plg ) {
			foreach ( new \DirectoryIterator( getcwd().DIRECTORY_SEPERATOR.'epan-components'.DIRECTORY_SEPERATOR.$plg['namespace'].DIRECTORY_SEPERATOR.'lib'.DIRECTORY_SEPERATOR.'Plugins' ) as $fileInfo ) {
				if ( $fileInfo->isDot() ) continue;
				if ( !in_array( $plg_url=$plg['namespace'].'/Plugins_'.str_replace( ".php", "", $fileInfo->getFilename() ), $this->website_plugins_array ) ) {
					$p = $this->add( $plg['namespace'].'/Plugins_'.str_replace( ".php", "", $fileInfo->getFilename() ) );
					$this->website_plugins_array[] = $plg_url;
					$this->website_plugins[] = $p;
				}
			}
		}
	}


	function exec_plugins( $event_hook, &$param ) {
		if ( !is_array( $param ) )
			$param_array = array( &$param );
		else
			$param_array = $param;

		// if(empty($this->website_plugins))
		//  throw $this->exception("Plugins Not loaded");

		foreach ( $this->website_plugins as $p ) {
			// echo $event_hook. " on ". $p ."<br/>";
			$p->hook( $event_hook, $param_array );
		}
		return;


		echo "Event: ". $event_hook . "<br/>";
		foreach ( $param_array as $prm ) {
			if ( $prm instanceof AbstractObject )
				echo $prm['name']. "<br/>";
			else
				echo $prm . "<br/>";
		}
	}

	function defaultTemplate() {
		if ( strpos( str_replace( "/", "_", $_GET['page'] ), 'owner_' )!==false ) {
			return array( 'owner' );
		}
		if ( strpos( str_replace( "/", "_", $_GET['page'] ), 'branch_' )!==false ) {
			return array( 'branch' );
		}
		// if ( strpos( str_replace( "/", "_", $_GET['page'] ), 'system_' )!==false ) {
		// 	return array( 'system' );
		// }
		return array( 'shared' );
	}

}

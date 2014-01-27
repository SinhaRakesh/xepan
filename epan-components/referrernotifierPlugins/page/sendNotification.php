<?php

class page_referrernotifierPlugins_page_sendNotification extends page_base_editorAjax {

	public $required_login = false;

	function init(){
		parent::init();
		
		$l=$this->api->locate('addons','referrernotifierPlugins', 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons','referrernotifierPlugins'),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);

		
		// Store this visit to database
		$ser=$this->add('referrernotifierPlugins/Model_SearchEngineRefral');
		$ser['epan_id']=$this->api->current_epan->id;
		$ser['search_engine_url']=$_POST['searchengineurl'];
		$ser['keywords']= $_POST['keywords'];
		$ser['is_mail_sent']=$_POST['send_email']=='Y'? 1:0;
		$ser->save();

		// Send Email if email to be send
		if($_POST['send_email'] != 'Y') exit;
				
		$tm=$this->add( 'TMail_Transport_PHPMailer' );
		$msg=$this->add( 'SMLite' );
		$msg->loadTemplate( 'mail/referrernotifierEmail' );
		$msg->trySet( 'epan', $this->api->current_epan['name'] );
		$msg->trySet( 'referrer', $_POST['searchengine'] );
		$msg->trySet( 'referrer_url', $_POST['searchengineurl'] );
		$msg->trySet( 'keywords', $_POST['keywords'] );

		$email_body=$msg->render();

		$subject ="Your Epan was searched on Search Engine";

		try{
			$tm->send( $this->api->current_epan['email_id'], "info@epan.in", $subject, $email_body ,false,null);
		}catch( phpmailerException $e ) {
			//throw $e;
			$this->api->js()->univ()->errorMessage( $e->errorMessage() )->execute();
		}catch( Exception $e ) {
			throw $e;
		}
		$this->js()->univ()->successMessage("Owner informed that SOMEONE is on site")->execute();

	}
}
<?php
namespace simplebillingApp;
class Model_Bill extends \Model_Table {
	var $table= "simplebillingApp_bill";
	function init(){
		parent::init();

		$this->addField('bill_no')->mandatory(true);
		$this->addField('bill_date')->type('date')->defaultValue(date('Y-m-d'));
		$this->hasOne('simplebillingApp/Party','party_id');
		$this->hasOne('simplebillingApp/Epan','epan_id');
		$this->addField('gross_total')->type('money');
		$this->addField('discount_amount');
		$this->addField('discount_remark');
		$this->addField('total')->type('money');
		$this->addField('tax_detail')->caption('Tax Percentage');
		$this->addField('tax_amount');
		$this->addField('net_amount');
		$this->addField('remark');
		$this->addField('bill_type')->enum(array('Sales','Purchase'));
		$this->hasMany('simplebillingApp/BillDetail','bill_id');
		$this->addHook('beforeSave',$this);


		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_bill=$this->add('simplebillingApp/Model_Bill');
		if($this->loaded()){
			$old_bill->addCondition('id','<>',$this->id);
		}
		$old_bill->addCondition('bill_no',$this['bill_no']);
		$old_bill->addCondition('bill_type',$this['bill_type']);
		$old_bill->tryLoadAny();
		if($old_bill->loaded())
			$this->api->js()->univ()->errorMessage('This Bill No Is Already User, Bill Not Updated')->execute();

			
	}

	function sendToParty(){
		$bill_view = $this->add('simplebillingApp/View_PrintBill');
		$this->addExpression('party_number')->set(function($m,$q){
			return $m->refSQL('simplebillingApp/party_id')->get('name');
		});

		$bill_view->setModel($this);


		$config=$this->add('simplebillingApp/Model_Configuration');
		$config->addCondition('epan_id',$this->api->auth->model->id);
		$config->tryLoadAny();
		$bill_view->recursiveRender();

		$subject =$config['invoice_email_subject']?:"Invoice From " . $config ['company_name_text'];
		$email_body=$config['invoice_email_message'].$bill_view->template->render();

		try{
			require_once("PHPMailer/class.phpmailer.php");
	        $mail = new \PHPMailer(true);
	        $mail->IsSMTP();
	        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
	        $mail->SMTPAuth   = true;//$this->api->getConfig("tmail/phpmailer/username", null)?true:false;                  // enable SMTP authentication
	        $mail->Host       = $config['email_host']; //$this->api->getConfig("tmail/smtp/host");
	        $mail->Port       = $config['email_port']; //$this->api->getConfig("tmail/smtp/port");
	        $mail->Username   = $config['email_username']; //$this->api->getConfig("tmail/phpmailer/username", null);
	        $mail->Password   = $config['email_password']; //$this->api->getConfig("tmail/phpmailer/password", null);
	        $mail->SMTPAuthSecure = 'ssl';
	        $mail->AddReplyTo($config['email_username'],$config['company_name_text']);
	        $mail->AddAddress($this->ref('party_id')->get('email'));
	        $mail->SetFrom($config['email_username'], $config['company_name_text']);
	        $mail->Subject = $subject;
	        $mail->MsgHTML($email_body);
	        $mail->AltBody = null;
	        $mail->IsHTML(true);
	        $mail->Send();
		}catch( phpmailerException $e ) {
			$this->api->js()->univ()->errorMessage( $e->errorMessage())->execute();
			// throw $e;
		}catch( Exception $e ) {
			$this->api->js()->univ()->errorMessage( $e->errorMessage())->execute();
			throw $e;
		}
	}
}
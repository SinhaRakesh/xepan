<?php
namespace simplebillingApp;

class Model_Configuration extends \Model_Table {
	var $table= "simplebillingApp_configuration";
	function init(){
		parent::init();

		$this->hasOne('simplebillingApp/Epan','epan_id');
		$this->addField('company_name_text')->caption('Company Name');
		$this->addField('company_name')->caption('Bill Header')->display(array('form'=>'RichText'));
		$this->add("filestore/Field_Image","company_logo_id")->type('image');
		$this->addField('company_address')->type('text')->display(array('form'=>'RichText'));
		$this->addField('company_terms')->caption('Company Terms & Conditions')->type('text')->display(array('form'=>'RichText'));
		$this->addField('applicable_taxes');
		$this->addField('bill_rows');
		$this->addField('send_email_on_invoicing')->type('boolean')->defaultValue(true);
		$this->addField('email_host')->defaultValue('ssl://smtp.gmail.com')->hint('your email host ie for google type "ssl://smtp.google.com"');
		$this->addField('email_port')->defaultValue('465')->hint('your email port ie for google use "465"');
		$this->addField('email_username')->hint('your email username');
		$this->addField('email_password')->type('password')->hint('your email password');
		$this->addField('invoice_email_subject');
		$this->addField('invoice_email_message')->caption('Welcome Message Above Invoice')
			->type('text')
			->display(array('form'=>'RichText'))
			->defaultValue('Dear Sir/Madam,<br/>')
			;


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

/**
 * The mail component for CMSGears cms module. It must be initialised for app using the name cmgCmsMailer.
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	//const MAIL_CONTACT		= "contact";

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $htmlLayout		= '@cmsgears/module-cart/common/mails/layouts/html';
	public $textLayout		= '@cmsgears/module-cart/common/mails/layouts/text';
	public $viewPath		= '@cmsgears/module-cart/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	/*
	public function sendContactMail( $contactForm ) {

		$mailProperties	= $this->mailProperties;
		$adminEmail		= $mailProperties->getSenderEmail();
		$adminName		= $mailProperties->getSenderName();

		$fromEmail		= $mailProperties->getContactEmail();
		$fromName		= $mailProperties->getContactName();

		// User Mail
		$this->getMailer()->compose( self::MAIL_CONTACT, [ 'coreProperties' => $this->coreProperties, FormsGlobal::FORM_CONTACT => $contactForm ] )
			->setTo( $contactForm->email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( $contactForm->subject )
			//->setTextBody( $contact->contact_message )
			->send();
	}
	*/
}

<?php
namespace cmsgears\cart\common\components;

/**
 * The mail component for CMSGears cart module.
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const MAIL_STATUS	= 'status';

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $htmlLayout	= '@cmsgears/module-cart/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-cart/common/mails/layouts/text';
	public $viewPath	= '@cmsgears/module-cart/common/mails/views';

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

	public function sendStatusMail( $order, $user = null ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();
		$status		= $order->getStatusStr();
		$user		= isset( $user ) ? $user : $order->creator;
		$toEmail	= $user->email;

		$this->getMailer()->compose( self::MAIL_STATUS, [ 'coreProperties' => $this->coreProperties, 'order' => $order, 'user' => $user ] )
			->setTo( $toEmail )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Order $status | " . $this->coreProperties->getSiteName() )
			->send();
	}
}

<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\components;

/**
 * The mail component for CMSGears cart module.
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const MAIL_ORDER_STATUS		= 'order/status';
	const MAIL_INVOICE_STATUS	= 'invoice/status';

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $htmlLayout	= '@cmsgears/module-core/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-core/common/mails/layouts/text';
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

	public function sendOrderStatusMail( $order, $pdf ) {

		$user		= $order->user;
		$email		= $user->email;
		$fromEmail  = $this->mailProperties->getSenderEmail();
		$fromName   = $this->mailProperties->getSenderName();

		if( empty( $email ) ) {

			return;
		}

		// Send Mail
		$mail = $this->getMailer()->compose( self::MAIL_ORDER_STATUS, [ 'coreProperties' => $this->coreProperties, 'order' => $order, 'user' => $user ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Order Status | " . $this->coreProperties->getSiteName() );

		if( $pdf ) {

			$mail->attachContent( $pdf, [ 'fileName' => "Order-{$order->code}.pdf", 'contentType' => 'application/pdf' ] );
		}

		$mail->send();
	}

	public function sendInvoiceStatusMail( $invoice, $pdf ) {

		$user		= $invoice->user;
		$email		= $user->email;
		$fromEmail  = $this->mailProperties->getSenderEmail();
		$fromName   = $this->mailProperties->getSenderName();

		if( empty( $email ) ) {

			return;
		}

		// Send Mail
		$mail = $this->getMailer()->compose( self::MAIL_ORDER_STATUS, [ 'coreProperties' => $this->coreProperties, 'invoice' => $invoice, 'user' => $user ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Invoice Status | " . $this->coreProperties->getSiteName() );

		if( $pdf ) {

			$mail->attachContent( $pdf, [ 'fileName' => "Invoice-{$invoice->code}.pdf", 'contentType' => 'application/pdf' ] );
		}

		$mail->send();
	}

}

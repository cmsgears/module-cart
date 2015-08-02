<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

class Mailer extends Component {

	// Various mail views used by the component
	//const MAIL_SUPPLIER_CREATE	= 'supplier-create';

    public $htmlLayout 	= '@cmsgears/cart/common/mails/layouts/html';
    public $textLayout 	= '@cmsgears/cart/common/mails/layouts/text';
    public $viewPath 	= '@cmsgears/cart/common/mails/views';

	private $mailer;

	/**
	 * Initialise the CMG Core Mailer.
	 */
    public function init() {

        parent::init();

        $this->mailer = Yii::$app->getMailer();

        $this->mailer->htmlLayout 	= $this->htmlLayout;
        $this->mailer->textLayout 	= $this->textLayout;
        $this->mailer->viewPath 	= $this->viewPath;
    }

	/**
	 * @return core mailer
	 */
	public function getMailer() {

		return $this->mailer;
	}

	// Admin Mails --------------

/*
	public function sendCreateDistrictMail( $coreProperties, $mailProperties, $district, $director ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_DISTRICT_CREATE, [ 'coreProperties' => $coreProperties, 'district' => $district, 'director' => $director ] )
            ->setTo( $director->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "District Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}
*/
	// Website Mails ------------
/*
	public function sendCreateEmployeeMail( $coreProperties, $mailProperties, $district, $employee ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_EMPLOYEE_CREATE, [ 'coreProperties' => $coreProperties, 'district' => $district, 'employee' => $employee ] )
            ->setTo( $employee->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Employee Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	} 
 */

}

?>
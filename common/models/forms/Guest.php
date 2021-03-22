<?php
namespace cmsgears\cart\common\models\forms;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Guest extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $firstName;
	public $lastName;
	public $email;
	public $mobile;

	public $captcha;
	public $captchaAction;	// Captcha url

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	public function rules() {

		$rules = [
			// Required, Safe
			[ [ 'firstName', 'lastName', 'email' ], 'required' ],
			[ 'mobile', 'required', 'on' => [ 'mobile', 'mobile-captcha' ] ],
			[ 'email', 'email' ],
			// Text Limit
			[ 'mobile', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'firstName', 'lastName' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'email', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ]
		];

		if( empty( $this->captchaAction ) ) {

			$this->captchaAction = '/cart/cart/captcha';
		}

		$rules[] = [ 'captcha', 'captcha', 'captchaAction' => $this->captchaAction, 'on' => [ 'captcha', 'mobile-captcha' ] ];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'firstName', 'lastName', 'email', 'mobile' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Guest ---------------------------------

}

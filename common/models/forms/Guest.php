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
	public $phone;

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
			[ 'phone', 'required', 'on' => [ 'phone', 'phone-captcha' ] ],
			// Text Limit
			[ [ 'email', 'phone' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			// Other
			[ 'email', 'email' ]
		];

		if( empty( $this->captchaAction ) ) {

			$this->captchaAction	= '/cart/cart/captcha';
		}

		$rules[] = [ 'captcha', 'captcha', 'captchaAction' => $this->captchaAction, 'on' => [ 'captcha', 'phone-captcha' ] ];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'firstName', 'lastName', 'email', 'phone' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Guest ---------------------------------

}

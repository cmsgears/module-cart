<?php
namespace cmsgears\cart\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

class Guest extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $firstname;
    public $lastname;
    public $email;

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

        $rules = [
        	[ [ 'firstname', 'lastname', 'email' ], 'required' ],
        	[ 'email', 'email' ]
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'firstname', 'lastname', 'email' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'email' => 'Email'
		];
	}
}

?>
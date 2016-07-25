<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\services\entities\UserService;

class GuestService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	// Data Provider ------

	// Create -----------

	public static function create( $form ) {

        $user   = new User();

        $user->firstName    = $form->firstname;
        $user->lastName     = $form->lastname;
        $user->email        = $form->email;

        $user   = UserService::create( $user );

        if( isset( $user ) ) {

            Yii::$app->cmgCore->setAppUser( $user );

            return $user;
        }

        return false;
	}

	// Update -----------

	// Delete -----------

}

?>
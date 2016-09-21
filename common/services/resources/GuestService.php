<?php
namespace cmsgears\cart\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;
use cmsgears\cart\common\services\interfaces\resources\IGuestService;

class GuestService extends \cmsgears\core\common\services\base\EntityService implements IGuestService {

	// Static Methods ----------------------------------------------

	// Read ----------------

	// Data Provider ------

	// Create -----------

	public function create( $form, $config = [] ) {

		$user	= new User();

		$user->firstName	= $form->firstName;
		$user->lastName		= $form->lastName;
		$user->email		= $form->email;

		$user	= Yii::$app->factory->get( 'userService' )->create( $user );

		if( isset( $user ) ) {

			Yii::$app->core->setAppUser( $user );

			return $user;
		}

		return false;
	}

	// Update -----------

	// Delete -----------

}

?>
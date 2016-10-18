<?php
namespace cmsgears\cart\common\services\forms;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\services\interfaces\entities\IUserService;
use cmsgears\cart\common\services\interfaces\forms\IGuestService;

class GuestService extends \yii\base\Component implements IGuestService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $userService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IUserService $userService, $config = [] ) {

		$this->userService	= $userService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GuestService --------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$user	= $this->userService->findByEmail( $model->email );

		if( !isset( $user ) ) {

			$user	= new User();

			$user->firstName	= $model->firstName;
			$user->lastName		= $model->lastName;
			$user->email		= $model->email;

			$user->save();
		}

		if( isset( $user ) ) {

			Yii::$app->core->setAppUser( $user );

			return $user;
		}

		return false;
	}

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// GuestService --------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}

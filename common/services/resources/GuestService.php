<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\services\interfaces\entities\IUserService;
use cmsgears\cart\common\services\interfaces\resources\IGuestService;

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

	public function create( $form, $config = [] ) {

		$user	= new User();

		$user->firstName	= $form->firstname;
		$user->lastName		= $form->lastname;
		$user->email		= $form->email;

		$user	= $this->userService->create( $user );

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

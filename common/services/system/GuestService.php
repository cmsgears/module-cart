<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\forms;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\services\interfaces\entities\IUserService;
use cmsgears\cart\common\services\interfaces\forms\IGuestService;

use cmsgears\core\common\services\base\SystemService;

/**
 * GuestService provide methods specific to guest checkout.
 *
 * @since 1.0.0
 */
class GuestService extends SystemService implements IGuestService {

	// Variables ---------------------------------------------------

	// Globals ----------------

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

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GuestService --------------------------

	public function create( $model, $config = [] ) {

		$user = $this->userService->getByEmail( $model->email );

		if( !isset( $user ) ) {

			$user = new User();

			$user->firstName	= $model->firstName;
			$user->lastName		= $model->lastName;
			$user->email		= $model->email;

			$user->save();
		}
		else {

			Yii::$app->core->setAppUser( $user );
		}

		return $user;
	}

}

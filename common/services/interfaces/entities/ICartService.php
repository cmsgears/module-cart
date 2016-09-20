<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ICartService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByToken( $token );

	public function getByUserId( $userId );

	public function createByUserId( $userId );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createByUserId( $userId );

	// Update -------------

	public function setAbandoned( $existingCart = null );

	public function updateStatus( $cart, $status );

	public function addItemToCart( $cart, $cartItem, $additionalParams = [] );

	// Delete -------------

}

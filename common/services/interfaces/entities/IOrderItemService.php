<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IOrderItemService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByOrderId( $oderId );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createFromCartItem( $order, $cartItem, $config = [] );

	// Update -------------

	// Delete -------------

}

<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IEntityService;

interface IOrderItemService extends IEntityService {

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

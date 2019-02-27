<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelResourceService;

/**
 * IOrderItemService declares methods specific to order items.
 *
 * @since 1.0.0
 */
interface IOrderItemService extends IModelResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByOrderId( $oderId );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromCartItem( $order, $cartItem, $config = [] );

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

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
 * ICartItemService declares methods specific to cart items.
 *
 * @since 1.0.0
 */
interface ICartItemService extends IModelResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCartId( $cartId );

	public function getByParentCartId( $parentId, $parentType, $cartId );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByCartId( $cartId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

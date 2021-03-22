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
use cmsgears\core\common\services\interfaces\base\IMultiSite;

/**
 * IOrderItemService declares methods specific to order items.
 *
 * @since 1.0.0
 */
interface ICartService extends IModelResourceService, IMultiSite {

	public function setCartItemService( ICartItemService $cartItemService );

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/**
	 * Find and return the cart using the given token. It creates the cart in case cart not found.
	 *
	 * @param string $token
	 */
	public function getByToken( $token );

	/**
	 * Find and return the cart using the given user id. It creates the cart in case cart not found.
	 *
	 * @param string $userId
	 */
	public function getByUserId( $userId );


	/**
	 * Find and return the cart using the given user id, parent id, and parent type. It creates
	 * the cart in case cart not found.
	 *
	 * @param string $userId
	 */
	public function getByUserIdParent( $userId, $parentId, $parentType );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function setAbandoned( $model );

	public function setSuccess( $model );

	// Delete -------------

	// Items --------------

	public function addItem( $model, $item, $config = [] );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

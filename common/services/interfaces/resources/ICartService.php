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
interface ICartService extends IModelResourceService {

	public function setCartItemService( ICartItemService $cartItemService );

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByToken( $token );

	public function getByModelToken( $model, $type );

	public function getByUserId( $userId );

	public function getByParent( $parentId, $parentType, $first = true );

	public function getByType( $parentId, $parentType, $type );

	public function getActiveByParent( $parentId, $parentType );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createByUserId( $userId );

	// Update -------------

	public function updateStatus( $model, $status, $config = [] );

	public function setAbandoned( $model, $config = [] );

	// Delete -------------

	// Items --------------

	public function addItem( $model, $item, $config = [] );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

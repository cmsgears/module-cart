<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\resources;

// CMG Imports
use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\OrderItem;

use cmsgears\cart\common\services\interfaces\resources\IOrderItemService;

use cmsgears\core\common\services\base\ResourceService;

/**
 * OrderItemService provide service methods of order item model.
 *
 * @since 1.0.0
 */
class OrderItemService extends ResourceService implements IOrderItemService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\entities\OrderItem';

	public static $modelTable	= CartTables::TABLE_ORDER_ITEM;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OrderItemService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByOrderId( $oderId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByOrderId( $oderId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Create Order Item from cart item
	public function createFromCartItem( $order, $cartItem, $config = [] ) {

		// Set Attributes
		$orderItem				= new OrderItem();
		$orderItem->orderId		= $order->id;
		$orderItem->createdBy	= $order->creator->id;

		// Copy from Cart Item
		$orderItem->copyForUpdateFrom( $cartItem, [ 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'parentId', 'parentType', 'name', 'price', 'primary', 'purchase', 'quantity', 'total', 'weight', 'volume', 'length', 'width', 'height', 'radius' ] );

		$orderItem->save();

		// Return OrderItem
		return $orderItem;
	}

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// OrderItemService ----------------------

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

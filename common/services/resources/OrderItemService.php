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

	public static $modelClass = '\cmsgears\cart\common\models\entities\OrderItem';

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

		$model	= $this->getModelObject();

		// Set Attributes
		$model->orderId		= $order->id;
		$model->createdBy	= $order->creator->id;

		// Copy from Cart Item
		$model->copyForUpdateFrom( $cartItem, [ 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'parentId', 'parentType', 'name', 'price', 'primary', 'purchase', 'quantity', 'total', 'weight', 'volume', 'length', 'width', 'height', 'radius' ] );

		$model->save();

		// Return OrderItem
		return $model;
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

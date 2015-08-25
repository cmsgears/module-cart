<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\entities\CartTables; 
use cmsgears\cart\common\models\entities\OrderItem;

class OrderItemService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------
	 
	// Read ---------------- 

	public static function findById( $id ) {
		
		return OrderItem::findById( $id );
	}

	public static function findByOrderId( $oderId ) {
		
		return OrderItem::findByOrderId( $oderId );
	}

	// Data Provider ------

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new OrderItem(), $config );
	}

	// Create -----------
	
	// Clone Order Item from cart item
	public static function createFromCartItem( $orderId, $cartItem, $additionalParams = [] ) {

		// Set Attributes
		$user					= Yii::$app->user->getIdentity();
		
		$orderItem				= new OrderItem();
		$orderItem->orderId		= $orderId;
		$orderItem->createdBy	= $user->id;

		// Regular Params
		$orderItem->copyForUpdateFrom( $cartItem, [ 'quantityUnitId', 'weightUnitId', 'metricUnitId', 'parentId', 'parentType', 'name', 'price', 'quantity', 'weight', 'length', 'width', 'height' ] );

		// Additional Params
		if( count( $additionalParams ) > 0 ) {

			$orderItem->copyForUpdateFrom( $cartItem, $additionalParams );
		}

		$orderItem->save();

		// Return OrderItem
		return $orderItem;
	}

	// Clone Order Item from other order's item
	public static function createFromOrderItem( $orderId, $orderItem, $additionalParams = [] ) {

		// Set Attributes
		$user					= Yii::$app->user->getIdentity();

		unset( $orderItem->id );

		$orderItemToSave				= new OrderItem();

		$orderItemToSave->orderId		= $orderId;
		$orderItemToSave->createdBy		= $user->id;

		// Regular Params
		$orderItemToSave->copyForUpdateFrom( $orderItem, [ 'quantityUnitId', 'weightUnitId', 'metricUnitId', 'parentId', 'parentType', 'name', 'price', 'quantity', 'weight', 'length', 'width', 'height' ] );

		// Additional Params
		if( count( $additionalParams ) > 0 ) {

			$orderItemToSave->copyForUpdateFrom( $orderItem, $additionalParams );
		}

		$orderItemToSave->save();

		// Return OrderItem
		return $orderItemToSave;
	}

	// Update ----------- 

	// Delete -----------

}

?>
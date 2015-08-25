<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\entities\CartTables; 
use cmsgears\cart\common\models\entities\CartItem;

class CartItemService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------
	 
	// Read ---------------- 

	public static function findById( $id ) {
		
		return CartItem::findById( $id );
	}

	public static function findByUserId( $userId ) {

		$cart			= CartService::findAndCreateByUserId( $userId );

		return CartItem::findByCartId( $cart->id );
	}

	public static function getObjectMapByUserId( $userId ) {
		
		$cart			= CartService::findAndCreateByUserId( $userId );

		return self::findObjectMap( 'parentId', new CartItem(), [ 'conditions' => [ 'cartId' => $cart->id ] ] );
	}

	// Data Provider ------

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new CartItem(), $config );
	}

	// Create -----------

	public static function create( $cartItem ) {

		// Set Attributes
		$user					= Yii::$app->user->getIdentity();

		$cartItem->createdBy	= $user->id;

		$cartItem->save();

		// Return CartItem
		return $cartItem;
	}

	// Update ----------- 

	public static function update( $cartItem, $additionalParams = [] ) {

		$user				= Yii::$app->user->getIdentity();
		$cartItemToUpdate	= self::findById( $cartItem->id );

		$cartItemToUpdate->modifiedBy	= $user->id;

		// Copy required params
		$cartItemToUpdate->copyForUpdateFrom( $cartItem, [ 'quantityUnitId', 'weightUnitId', 'metricUnitId', 'price', 'quantity', 'weight', 'length', 'width', 'height' ] );

		// Copy Additional Params
		$cartItemToUpdate->copyForUpdateFrom( $cartItem, $additionalParams );

		// Update CartItem
		$cartItemToUpdate->update();

		// Return CartItem
		return $cartItemToUpdate;
	}
 
	// Delete -----------

	public static function delete( $cartItem ) {

		$cartItemToDelete	= self::findById( $cartItem->id );

		// Delete CartItem
		$cartItemToDelete->delete();

		// Return true
		return true;
	}
	
	public static function deleteByCartId( $cartId ) {
		
		CartItem::deleteByCartId( $cartId );
	}
}

?>
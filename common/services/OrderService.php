<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\Address;
 
use cmsgears\cart\common\models\entities\CartTables;
use cmsgears\cart\common\models\entities\Order;
use cmsgears\cart\common\models\entities\OrderItem;

use cmsgears\core\common\services\ModelAddressService;
use cmsgears\cart\common\services\OrderHistoryService;

class OrderService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------
	 
	// Read ---------------- 

	public static function findById( $id ) {
		
		return Order::findById( $id );
	}

	// Data Provider ------

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Order(), $config );
	}

	// Create -----------

	public static function create( $order, $shippingAddress, $cart, $cartItems, $message, $additionalParams = [] ) {

		// Set Attributes
		$user				= Yii::$app->user->getIdentity();

		$order->createdBy	= $user->id;
		$order->status		= Order::STATUS_NEW;
		$order->description	= $message;

		// Generate uid
		$order->generateName();

		// Set Order Totals
		$cartTotal			= $cart->getCartTotal( $cartItems );

		$order->subTotal	= $cartTotal;
		$order->tax			= 0;
		$order->shipping	= 0;
		$order->total		= $cartTotal;
		$order->discount	= 0;
		$order->grandTotal	= $cartTotal;

		$order->save();

		// Save Shipping Address
		$address			= new Address();

		$address->copyForUpdateFrom( $shippingAddress, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'city', 'zip', 'firstName', 'lastName', 'phone', 'email', 'fax' ] );

		ModelAddressService::create( $address, $order->id, CartGlobal::TYPE_ORDER, Address::TYPE_SHIPPING );

		// Create Order Items
		foreach ( $cartItems as $cartItem ) {

			$orderItem	= new OrderItem();

			OrderItemService::create( $order->id, $orderItem, $cartItem, $additionalParams );
		}
		
		// Delete Cart Items
		CartItemService::deleteByCartId( $cart->id );

		// Delete Cart
		CartService::delete( $cart );
		
		// Create Order History
		OrderHistoryService::create( $order );

		// Return Order
		return $order;
	}

	// Update ----------- 

	public static function updateStatus( $order, $status ) {

		$user				= Yii::$app->user->getIdentity();
		$order				= self::findById( $order->id );

		$order->modifiedBy	= $user->id;
		$order->status		= $status;

		$order->update();

		// Create Order History
		OrderHistoryService::create( $order );
	}

	public static function confirmOrder( $order ) {

		self::updateStatus( $order, Order::STATUS_CONFIRMED );
	}

	public static function placeOrder( $order ) {
		
		self::updateStatus( $order, Order::STATUS_PLACED );
	}

	public static function updateStatusToPaid( $order ) {

		self::updateStatus( $order, Order::STATUS_PAID );
	}
}

?>
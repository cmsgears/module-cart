<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\entities\OrderItem;

use cmsgears\cart\common\services\interfaces\entities\IOrderService;
use cmsgears\cart\common\services\interfaces\entities\IOrderItemService;

class OrderItemService extends \cmsgears\core\common\services\base\EntityService implements IOrderItemService {

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

	protected $orderService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IOrderService $orderService, $config = [] ) {

		$this->orderService	= $orderService;

		parent::__construct( $config );
	}

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

		return OrderItem::findByOrderId( $oderId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Create Order Item from cart item
	public function createFromCartItem( $order, $cartItem, $config = [] ) {

		// Set Attributes
		$user					= Yii::$app->core->getAppUser();

		$orderItem				= new OrderItem();
		$orderItem->orderId		= $order->id;
		$orderItem->createdBy	= $user->id;

		// Copy from Cart Item
		$orderItem->copyForUpdateFrom( $cartItem, [ 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'parentId', 'parentType', 'name', 'price', 'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius' ] );

		$orderItem->save();

		// Return OrderItem
		return $orderItem;
	}

	// Update -------------

	// Delete -------------

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

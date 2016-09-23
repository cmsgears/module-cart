<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\entities\OrderItem;

use cmsgears\cart\common\services\interfaces\entities\IOrderService;
use cmsgears\cart\common\services\interfaces\entities\IOrderItemService;

class OrderService extends \cmsgears\core\common\services\base\EntityService implements IOrderItemService {

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

	// Clone Order Item from cart item
	public function createFromCartItem( $orderId, $cartItem, $additionalParams = [] ) {

		// Set Attributes
		$user					= Yii::$app->core->getAppUser();

		$orderItem				= new OrderItem();
		$orderItem->orderId		= $orderId;
		$orderItem->createdBy	= $user->id;

		// Regular Params
		$orderItem->copyForUpdateFrom( $cartItem, [ 'quantityUnitId', 'weightUnitId', 'lengthUnitId', 'parentId', 'parentType', 'name', 'price', 'quantity', 'weight', 'length', 'width', 'height' ] );

		// Additional Params
		if( count( $additionalParams ) > 0 ) {

			$orderItem->copyForUpdateFrom( $cartItem, $additionalParams );
		}

		$orderItem->save();

		// Return OrderItem
		return $orderItem;
	}

	// Clone Order Item from other order's item
	public function createFromOrderItem( $orderId, $orderItem, $additionalParams = [] ) {

		// Set Attributes
		$user					= Yii::$app->core->getAppUser();

		unset( $orderItem->id );

		$orderItemToSave				= new OrderItem();

		$orderItemToSave->orderId		= $orderId;
		$orderItemToSave->createdBy		= $user->id;

		// Regular Params
		$orderItemToSave->copyForUpdateFrom( $orderItem, [ 'quantityUnitId', 'weightUnitId', 'lengthUnitId', 'parentId', 'parentType', 'name', 'price', 'quantity', 'weight', 'length', 'width', 'height' ] );

		// Additional Params
		if( count( $additionalParams ) > 0 ) {

			$orderItemToSave->copyForUpdateFrom( $orderItem, $additionalParams );
		}

		$orderItemToSave->save();

		// Return OrderItem
		return $orderItemToSave;
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

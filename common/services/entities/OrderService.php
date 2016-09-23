<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\entities\Order;

use cmsgears\core\common\services\interfaces\mappers\IModelAddressService;
use cmsgears\cart\common\services\interfaces\entities\ICartService;
use cmsgears\cart\common\services\interfaces\entities\IOrderService;
use cmsgears\cart\common\services\interfaces\entities\IOrderItemService;

class OrderService extends \cmsgears\core\common\services\base\EntityService implements IOrderService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\entities\Order';

	public static $modelTable	= CartTables::TABLE_ORDER;

	public static $parentType	= CartGlobal::TYPE_ORDER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $cartService;

	protected $orderItemService;

	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( ICartService $cartService, IModelAddressService $modelAddressService, $config = [] ) {

		$this->cartService			= $cartService;

		$this->modelAddressService	= $modelAddressService;

		parent::__construct( $config );
	}

	public function setOrderItemService( IOrderItemService $orderItemService ) {

		$this->orderItemService	= $orderItemService;
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OrderService --------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'name'
				]
			]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	public function getPageByParent( $parentId, $parentType ) {

		$modelTable	= self::$modelTable;

		return $this->getpage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType ] ] );
	}

	// Read ---------------

	public function getByTitle( $title ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByTitle( $title );
	}

	public function getCountByParent( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::queryByParent( $parentId, $parentType )->count();
	}

	public function getCountByUserId( $userId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::queryByCreatorId( $userId )->count();
	}

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromCart( $order, $shippingAddress, $cart, $cartItems, $message, $additionalParams = [] ) {

		// Set Attributes
		$user				= Yii::$app->cmgCore->getAppUser();

		$order->createdBy	= $user->id;
		$order->status		= Order::STATUS_NEW;
		$order->description	= $message;

		// Generate uid
		$order->generateName();

		// Set Order Totals
		$cartTotal			= $cart->getCartTotal( $cartItems );

		$order->subTotal	= $cartTotal;
		$order->parentId	= $cart->parentId;
		$order->parentType	= $cart->parentType;
		$order->tax			= 0;
		$order->shipping	= 0;
		$order->total		= $cartTotal;
		$order->discount	= 0;
		$order->grandTotal	= $cartTotal;

		$order->save();

		// Save Shipping Address
		$this->modelAddressService->createOrUpdateByType( $shippingAddress, [ 'parentId' => $order->id, 'parentType' => CartGlobal::TYPE_ORDER, 'type' => Address::TYPE_SHIPPING ]);

		// Create Order Items
		foreach ( $cartItems as $cartItem ) {

			$this->orderItemService->createFromCartItem( $order->id, $cartItem, $additionalParams );
		}

		// Delete Cart Items
		$this->cartItemService->deleteByCartId( $cart->id );

		// Delete Cart
		$this->cartService->delete( $cart );

		// Return Order
		return $order;
	}

	// Update -------------

	public function updateStatus( $model, $status ) {

		$user				= Yii::$app->core->getAppUser();
		$model				= self::findById( $model->id );

		$model->modifiedBy	= $user->id;
		$model->status		= $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function confirmOrder( $order ) {

		self::updateStatus( $order, Order::STATUS_CONFIRMED );
	}

	public function placeOrder( $order ) {

		self::updateStatus( $order, Order::STATUS_PLACED );
	}

	public function updateStatusToPaid( $order ) {

		self::updateStatus( $order, Order::STATUS_PAID );
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// OrderService --------------------------

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

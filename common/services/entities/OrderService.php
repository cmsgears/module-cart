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
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\services\interfaces\mappers\IModelAddressService;
use cmsgears\cart\common\services\interfaces\entities\ICartService;
use cmsgears\cart\common\services\interfaces\entities\ICartItemService;
use cmsgears\cart\common\services\interfaces\entities\IOrderService;
use cmsgears\cart\common\services\interfaces\entities\IOrderItemService;

use cmsgears\core\common\utilities\DateUtil;

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

	protected $cartItemService;

	protected $orderItemService;

	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( ICartService $cartService, ICartItemService $cartItemService, IModelAddressService $modelAddressService, $config = [] ) {

		$this->cartService			= $cartService;
		$this->cartItemService		= $cartItemService;

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
				'title' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => ['title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => ['title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'status' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => ['title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				]
			]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	public function getPageByParent( $parentId, $parentType ) {

		$modelTable	= static::$modelTable;

		return $this->getpage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType ] ] );
	}

	// Read ---------------

	public function getByTitle( $title ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByTitle( $title );
	}

	public function getCountByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByParent( $parentId, $parentType )->count();
	}

	public function getCountByUserId( $userId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByCreatorId( $userId )->count();
	}

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromCart( $order, $message, $cart, $config = [] ) {

		// Init Transaction
		$transaction		= Yii::$app->db->beginTransaction();

		// Set Attributes
		$user				= Yii::$app->core->getAppUser();
		$billingAddress		= isset( $config[ 'billingAddress' ] ) ? $config[ 'billingAddress' ] : null;
		$shippingAddress	= isset( $config[ 'shippingAddress' ] ) ? $config[ 'shippingAddress' ] : null;

		// Order
		$order->createdBy	= $user->id;
		$order->parentId	= $cart->parentId;
		$order->parentType	= $cart->parentType;
		$order->status		= Order::STATUS_NEW;
		$order->description	= $message;

		// Generate UID if required
		if( !isset( $order->title ) ) {

			$order->generateName();
		}

		// Set Order Totals
		$order->subTotal	= $cart->getCartTotal();
		$order->discount	= 0;
		$order->tax			= 0;
		$order->shipping	= 0;
		$order->total		= $order->subTotal + $order->tax + $order->shipping;
		$order->grandTotal	= $order->total - $order->discount;

		try {

			// Create Order
			$order->save();

			// Create Billing Address
			if( isset( $billingAddress ) ) {

				$this->modelAddressService->createOrUpdateByType( $billingAddress, [ 'parentId' => $order->id, 'parentType' => CartGlobal::TYPE_ORDER, 'type' => Address::TYPE_BILLING ] );
			}

			// Create Shipping Address
			if( !$order->shipToBilling && isset( $shippingAddress ) ) {

				$this->modelAddressService->createOrUpdateByType( $shippingAddress, [ 'parentId' => $order->id, 'parentType' => CartGlobal::TYPE_ORDER, 'type' => Address::TYPE_SHIPPING ] );
			}

			// Create Order Items
			$cartItems	= $cart->items;

			foreach ( $cartItems as $item ) {

				Yii::$app->factory->get( 'orderItemService' )->createFromCartItem( $order, $item, $config );
			}

			// Delete Cart & Cart Items
			$this->cartService->delete( $cart );

			// Commit Order
			$transaction->commit();
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		// Return Order
		return $order;
	}

	// Update -------------

	public function updateStatus( $model, $status ) {

		$user				= Yii::$app->core->getAppUser();
		$model				= static::findById( $model->id );

		$model->modifiedBy	= $user->id;
		$model->status		= $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function cancel( $order, $checkChildren = true, $checkBase = true ) {

		// Cancel all child orders
		if( $checkChildren ) {

			$children	= $order->children;

			foreach ( $children as $child ) {

				$this->updateStatus( $child, Order::STATUS_CANCELLED );
			}
		}

		// Cancel order
		$this->updateStatus( $order, Order::STATUS_CANCELLED );

		// Cancel parent
		if( $checkBase && $order->hasBase() ) {

			$base		= $order->base;
			$children	= $base->children;
			$cancel		= true;

			// No cancel if at least one child is not cancelled
			foreach ( $children as $child ) {

				if( !$child->isCancelled( true ) ) {

					$cancel = false;

					break;
				}
			}

			if( $cancel ) {

				$this->updateStatus( $base, Order::STATUS_CANCELLED );
			}
		}
	}

	public function approve( $order ) {

		$this->updateStatus( $order, Order::STATUS_APPROVED );
	}

	public function place( $order ) {

		$this->updateStatus( $order, Order::STATUS_PLACED );
	}

	public function paid( $order ) {

		$this->updateStatus( $order, Order::STATUS_PAID );
	}

	public function confirm( $order ) {

		$this->updateStatus( $order, Order::STATUS_CONFIRMED );
	}

	public function process( $order ) {

		$this->updateStatus( $order, Order::STATUS_PROCESSED );
	}

	public function ship( $order ) {

		$this->updateStatus( $order, Order::STATUS_SHIPPED );
	}

	public function deliver( $order ) {

		$order->deliveredAt = DateUtil::getDateTime();

		$order->update();

		$this->updateStatus( $order, Order::STATUS_DELIVERED );
	}

	public function complete( $order ) {

		$this->updateStatus( $order, Order::STATUS_COMPLETED );
	}

	public function updateBaseStatus( $order ) {

		$children	= $order->children;
		$completed	= true;

		foreach ( $children as $child ) {

			if( !$child->isCompleted() ) {

				$completed	= false;

				break;
			}
		}

		if( $completed ) {

			$this->complete( $order );
		}
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

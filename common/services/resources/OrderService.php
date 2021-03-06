<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\resources\Address;
use cmsgears\cart\common\models\resources\Order;

use cmsgears\core\common\services\interfaces\mappers\IModelAddressService;
use cmsgears\cart\common\services\interfaces\resources\ICartService;
use cmsgears\cart\common\services\interfaces\resources\ICartItemService;
use cmsgears\cart\common\services\interfaces\resources\IOrderService;
use cmsgears\cart\common\services\interfaces\resources\IOrderItemService;

use cmsgears\core\common\services\base\ModelResourceService;

use cmsgears\core\common\utilities\DateUtil;

/**
 * OrderService provide service methods of order model.
 *
 * @since 1.0.0
 */
class OrderService extends ModelResourceService implements IOrderService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\resources\Order';

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
/*
	public function __construct( ICartService $cartService, ICartItemService $cartItemService, IOrderItemService $orderItemService, IModelAddressService $modelAddressService, $config = [] ) {

		$this->cartService			= $cartService;
		$this->cartItemService		= $cartItemService;

		$this->orderItemService		= $orderItemService;

		$this->modelAddressService	= $modelAddressService;

		parent::__construct( $config );
	}
*/
	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OrderService --------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'status' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'total' => [
					'asc' => [ "$modelTable.grandTotal" => SORT_ASC ],
					'desc' => [ "$modelTable.grandTotal" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Total'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				]
			],
			'defaultOrder' => [ 'cdate' => 'SORT_ASC' ]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) ) {

			if( isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

				$config[ 'conditions' ][ "$modelTable.status" ] = $modelClass::$urlRevStatusMap[ $status ];
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'title' => "$modelTable.title", 'description' => "$modelTable.description" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title", 'description' => "$modelTable.description", 'content' => "$modelTable.content"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.createdBy" => $userId ] ] );
	}

	public function getPageByUserIdParentType( $userId, $parentType ) {

		$modelTable	= $this->getModelTable();

		$paid = Order::STATUS_PAID;

		$config = [ 'conditions' => [ "$modelTable.status >= $paid", "$modelTable.createdBy" => $userId, "$modelTable.parentType" => $parentType ] ];

		return $this->getPage( $config );
	}

	// Read ---------------

	public function getCountByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByParent( $parentId, $parentType )->count();
	}

	public function getCountByUserId( $userId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByCreatorId( $userId )->count();
	}

	// Read - Models ---

	/**
	 * Use only if title is unique for order.
	 *
	 * @param string $title
	 * @return Order
	 */
	public function getByTitle( $title ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByTitle( $title );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromCart( $order, $cart, $config = [] ) {

		// Init Transaction
		$transaction = Yii::$app->db->beginTransaction();

		// Set Attributes
		$billingAddress		= isset( $config[ 'billingAddress' ] ) ? $config[ 'billingAddress' ] : null;
		$shippingAddress	= isset( $config[ 'shippingAddress' ] ) ? $config[ 'shippingAddress' ] : null;

		// Order
		$order->parentId	= $cart->parentId;
		$order->parentType	= $cart->parentType;
		$order->type		= $cart->type;
		$order->status		= $order->status ?? Order::STATUS_NEW;

		// Generate UID if required
		if( !isset( $order->title ) ) {

			$order->generateTitle();
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
			$cartItems	= $cart->activeItems;

			foreach ( $cartItems as $item ) {

				$this->orderItemService->createFromCartItem( $order, $item, $config );
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

		$model->status	= $status;

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

	public function back( $order ) {

		$this->updateStatus( $order, Order::STATUS_RETURNED );
	}

	public function dispute( $order ) {

		$this->updateStatus( $order, Order::STATUS_DISPUTE );
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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

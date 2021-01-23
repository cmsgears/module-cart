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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;
use cmsgears\cart\common\config\CartProperties;

use cmsgears\cart\common\models\resources\Order;

use cmsgears\cart\common\services\interfaces\resources\IOrderService;

use cmsgears\core\common\services\traits\base\MultiSiteTrait;
use cmsgears\core\common\services\traits\base\StatusTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * OrderService provide service methods of order model.
 *
 * @since 1.0.0
 */
class OrderService extends \cmsgears\core\common\services\base\ModelResourceService implements IOrderService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\cart\common\models\resources\Order';

	public static $parentType = CartGlobal::TYPE_ORDER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $cartService;

	protected $cartItemService;

	protected $orderItemService;

	protected $addressService;
	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use MultiSiteTrait;
	use StatusTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->cartService		= Yii::$app->factory->get( 'cartService' );
		$this->cartItemService	= Yii::$app->factory->get( 'cartItemService' );

		$this->orderItemService = Yii::$app->factory->get( 'orderItemService' );

		$this->addressService = Yii::$app->factory->get( 'addressService' );

		$this->modelAddressService = Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OrderService --------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

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
			'defaultOrder' => $defaultSort
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

		// Filter - Status
		if( isset( $status ) && empty( $config[ 'conditions' ][ "$modelTable.status" ] ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByUserId( $userId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		return $this->getPage( $config );
	}

	public function getPageByUserIdType( $userId, $type, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

		return $this->getPage( $config );
	}

	public function getPageByUserIdParentType( $userId, $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		$config[ 'conditions' ][ "$modelTable.parentType" ] = $parentType;

		return $this->getPage( $config );
	}

	// Read ---------------

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

	public function getCountByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByParent( $parentId, $parentType )->count();
	}

	public function getCountByUserId( $userId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByUserId( $userId )->count();
	}

	// Create -------------

	public function createFromCart( $order, $cart, $config = [] ) {

		$cartProperties = CartProperties::getInstance();

		$addressClass = $this->addressService->getModelClass();

		// Set Attributes
		$billingAddress		= isset( $config[ 'billingAddress' ] ) ? $config[ 'billingAddress' ] : null;
		$shippingAddress	= isset( $config[ 'shippingAddress' ] ) ? $config[ 'shippingAddress' ] : null;

		// Order
		$order->parentId	= $cart->parentId;
		$order->parentType	= $cart->parentType;
		$order->type		= $cart->type;
		$order->status		= $order->status ?? Order::STATUS_NEW;

		// Generate UID if required
		if( empty( $order->title ) ) {

			$order->generateTitle();
		}

		// Set Order Totals
		$order->subTotal	= $cart->getCartTotal();
		$order->discount	= 0;
		$order->tax			= 0;
		$order->shipping	= 0;
		$order->total		= $order->subTotal + $order->tax + $order->shipping;
		$order->grandTotal	= $order->total - $order->discount;

		// Init Transaction
		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Create Order
			$order->save();

			// Create Billing Address
			if( isset( $billingAddress ) ) {

				$address = $this->addressService->create( $billingAddress );

				$this->modelAddressService->activateByParentModelId( $order->id, static::$parentType, $address->id, $addressClass::TYPE_BILLING );
			}

			// Create Shipping Address
			if( !$order->shipToBilling && isset( $shippingAddress ) ) {

				$address = $this->addressService->create( $shippingAddress );

				$this->modelAddressService->activateByParentModelId( $order->id, static::$parentType, $address->id, $addressClass::TYPE_SHIPPING );
			}

			// Create Order Items
			$cartItems = $cart->activeItems;

			foreach( $cartItems as $item ) {

				$this->orderItemService->createFromCartItem( $order, $item, $config );
			}

			// Delete Cart & Cart Items
			if( $cartProperties->isRemoveCart() ) {

				$this->cartService->delete( $cart );
			}
			else {

				$this->cartService->setSuccess( $cart );
			}

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

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'code', 'service', 'title', 'description',
			'shipToBilling', 'eta', 'deliveredAt', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status' ] );
		}

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'data' ][ 'message' ] = 'Order status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	public function updateCode( $model, $code ) {

		$model->code = $code;

		return parent::update( $model, [
			'attributes' => [ 'code' ]
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => [ 'status' ]
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'data' ][ 'message' ] = 'Order status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	public function processCancel( $model, $checkChildren = true, $checkBase = true ) {

		// Cancel all child orders
		if( $checkChildren ) {

			$children = $model->children;

			foreach( $children as $child ) {

				$this->updateStatus( $child, Order::STATUS_CANCELLED );
			}
		}

		// Cancel order
		$this->updateStatus( $model, Order::STATUS_CANCELLED );

		// Cancel parent
		if( $checkBase && $model->hasBase() ) {

			$base		= $model->base;
			$children	= $base->children;
			$cancel		= true;

			// No cancel if at least one child is not cancelled
			foreach( $children as $child ) {

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

	public function approve( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_APPROVED );
	}

	public function place( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_PLACED );
	}

	public function hold( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_HOLD );
	}

	public function reject( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_APPROVED );
	}

	public function cancel( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_CANCELLED );
	}

	public function fail( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_FAILED );
	}

	public function paid( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_PAID );
	}

	public function refund( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_REFUNDED );
	}

	public function confirm( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_CONFIRMED );
	}

	public function process( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_PROCESSED );
	}

	public function ship( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_SHIPPED );
	}

	public function deliver( $model, $config = [] ) {

		if( !$model->isDelivered() ) {

			$model->deliveredAt = DateUtil::getDateTime();

			$model->update();
		}

		$this->updateStatus( $model, Order::STATUS_DELIVERED );
	}

	public function back( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_RETURNED );
	}

	public function dispute( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_DISPUTE );
	}

	public function complete( $model, $config = [] ) {

		$this->updateStatus( $model, Order::STATUS_COMPLETED );
	}

	public function updateBaseStatus( $model, $config = [] ) {

		$children	= $model->children;
		$completed	= true;

		foreach( $children as $child ) {

			if( !$child->isCompleted() ) {

				$completed = false;

				break;
			}
		}

		if( $completed ) {

			$this->complete( $model );
		}
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'place': {

						$this->place( $model );

						break;
					}
					case 'hold': {

						$this->hold( $model );

						break;
					}
					case 'cancel': {

						$this->cancelled( $model );

						break;
					}
					case 'approve': {

						$this->approve( $model );

						break;
					}
					case 'paid': {

						$this->paid( $model );

						break;
					}
					case 'confirm': {

						$this->confirm( $model );

						break;
					}
					case 'process': {

						$this->process( $model );

						break;
					}
					case 'ship': {

						$this->ship( $model );

						break;
					}
					case 'deliver': {

						$this->deliver( $model );

						break;
					}
					case 'back': {

						$this->back( $model );

						break;
					}
					case 'dispute': {

						$this->dispute( $model );

						break;
					}
					case 'complete': {

						$this->complete( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

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

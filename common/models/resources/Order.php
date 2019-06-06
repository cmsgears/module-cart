<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\mappers\IAddress;

use cmsgears\core\common\models\base\ModelResource;
use cmsgears\payment\common\models\base\PaymentTables;
use cmsgears\payment\common\models\resources\Transaction;
use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Order represents order either placed by user or created as part of an order specific process.
 *
 * @property integer $id
 * @property integer $baseId
 * @property integer $cartId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property float $subTotal
 * @property float $tax
 * @property float $shipping
 * @property float $total
 * @property float $discount
 * @property float $grandTotal
 * @property boolean $shipToBilling
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $eta
 * @property datetime $deliveredAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Order extends ModelResource implements IAddress, IAuthor, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_NEW		=	  0; // Order is created
	const STATUS_APPROVED	=  1000; // Order need approval
	const STATUS_PLACED		=  2000; // Order is placed
    const STATUS_HOLD       =  2500; // Order on hold
	const STATUS_CANCELLED	=  3000; // Order cancelled - no money return
	const STATUS_FAILED		=  3500; // Payment is failed
	const STATUS_PAID		=  4000; // Payment is done
	const STATUS_REFUNDED	=  5000; // Order refunded - money returned
	const STATUS_CONFIRMED	=  6000; // Confirmed by vendor
	const STATUS_PROCESSED	=  7000; // Processed by vendor
	const STATUS_SHIPPED	=  8000; // Shipped by vendor
	const STATUS_DELIVERED	=  9000; // Delivered by vendor
	const STATUS_RETURNED	= 10000; // Returned to vendor - no receiver
	const STATUS_DISPUTE	= 11000; // Order dispute
	const STATUS_COMPLETED	= 12000; // Order completed

	// Public -----------------

	public static $statusMap = array(
		self::STATUS_NEW  => 'New',
		self::STATUS_APPROVED => 'Approved',
		self::STATUS_PLACED => 'Placed',
        self::STATUS_HOLD => 'Hold',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_FAILED => 'Failed',
		self::STATUS_PAID => 'Paid',
		self::STATUS_REFUNDED => 'Refunded',
		self::STATUS_CONFIRMED => 'Confirmed',
		self::STATUS_PROCESSED => 'Processed',
		self::STATUS_SHIPPED => 'Shipped',
		self::STATUS_DELIVERED => 'Delivered',
		self::STATUS_RETURNED => 'Returned',
		self::STATUS_DISPUTE => 'Dispute',
		self::STATUS_COMPLETED => 'Completed'
		);

	// Used for external docs
	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Approved' => self::STATUS_APPROVED,
        'Hold' => self::STATUS_HOLD,
		'Placed' => self::STATUS_PLACED,
		'Cancelled' => self::STATUS_CANCELLED,
		'Failed' => self::STATUS_FAILED,
		'Paid' => self::STATUS_PAID,
		'Refunded' => self::STATUS_REFUNDED,
		'Confirmed' => self::STATUS_CONFIRMED,
		'Processed' => self::STATUS_PROCESSED,
		'Shipped' => self::STATUS_SHIPPED,
		'Delivered' => self::STATUS_DELIVERED,
		'Returned' => self::STATUS_RETURNED,
		'Dispute' => self::STATUS_DISPUTE,
		'Completed'  => self::STATUS_COMPLETED
	];

	// Used for url params
	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'approved' => self::STATUS_APPROVED,
        'hold' => self::STATUS_HOLD,
		'placed' => self::STATUS_PLACED,
		'cancelled' => self::STATUS_CANCELLED,
		'paid' => self::STATUS_PAID,
		'refunded' => self::STATUS_REFUNDED,
		'confirmed' => self::STATUS_CONFIRMED,
		'processed' => self::STATUS_PROCESSED,
		'shipped' => self::STATUS_SHIPPED,
		'delivered' => self::STATUS_DELIVERED,
		'returned' => self::STATUS_RETURNED,
		'dispute' => self::STATUS_DISPUTE,
		'completed' => self::STATUS_COMPLETED
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType = CartGlobal::TYPE_ORDER;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use AddressTrait;
	use AuthorTrait;
	use GridCacheTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'authorBehavior' => [
				'class' => AuthorBehavior::class
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ 'title', 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type', 'token' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'subTotal', 'tax', 'shipping', 'total', 'discount', 'grandTotal' ], 'number', 'min' => 0 ],
			[ [ 'shipToBilling', 'gridCacheValid' ], 'boolean' ],
			[ [ 'baseId', 'cartId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'eta', 'deliveredAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'baseId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PARENT_ORDER ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'subTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_SUB ),
			'tax' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'shipping' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIPPING ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'grandTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_GRAND ),
			'shipToBilling' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIP_TO_BILLING ),
			'eta' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_ESTIMATED_DELIVERY ),
			'deliveredAt' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DELIVERY_DATE ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Order ---------------------------------

	/**
	 * Check whether order is child order.
	 *
	 * @return boolean
	 */
	public function hasBase() {

		return isset( $this->baseId ) && $this->baseId > 0;
	}

	/**
	 * Returns the parent order.
	 *
	 * @return Order
	 */
	public function getBase() {

		$orderTable = CartTables::getTableName( CartTables::TABLE_ORDER );

		return $this->hasOne( Order::class, [ 'id' => 'baseId' ] )->from( "$orderTable as base" );
	}

	/**
	 * Check whether order has child orders.
	 *
	 * @return boolean
	 */
	public function hasChildren() {

		return count( $this->children ) > 0;
	}

	/**
	 * Returns all the child orders associated with the order.
	 *
	 * @return Order[]
	 */
	public function getChildren() {

		return $this->hasMany( Order::class, [ 'baseId' => 'id' ] );
	}

	public function getCart() {

		return $this->hasOne( Cart::class, [ 'id' => 'orderId' ] );
	}

	/**
	 * Returns the successful transaction associated with the order.
	 *
	 * It's useful in the cases where only one transaction is required for an order.
	 *
	 * @return Transaction
	 */
	public function getTransaction() {

		$transactionTable	= PaymentTables::getTableName( PaymentTables::TABLE_TRANSACTION );
		$transactionSuccess = Transaction::STATUS_SUCCESS;

		return $this->hasOne( Transaction::class, [ 'orderId' => 'id' ] )->where( "$transactionTable.status=$transactionSuccess" );
	}

	/**
	 * Returns all the transactions associated with the order.
	 *
	 * @return Transaction[]
	 */
	public function getTransactions() {

		return $this->hasMany( Transaction::class, [ 'orderId' => 'id' ] );
	}

	/**
	 * Returns the items associated with the order.
	 *
	 * @return OrderItem[]
	 */
	public function getItems() {

		return $this->hasMany( OrderItem::class, [ 'orderId' => 'id' ] );
	}

	/**
	 * Generate and set the title of cart.
	 *
	 * @return void
	 */
	public function generateTitle() {

		$this->title = Yii::$app->security->generateRandomString( 16 );
	}

	/**
	 * Returns string representation of the order.
	 *
	 * @return string
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	/**
	 * Check whether order is new.
	 *
	 * @return boolean
	 */
	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	/**
	 * Check whether order is new.
	 *
	 * @return boolean
	 */
	public function isHold() {

		return $this->status == self::STATUS_HOLD;
	}

	/**
	 * Check whether order is approved.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isApproved( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_APPROVED;
		}

		return $this->status >= self::STATUS_APPROVED;
	}

	/**
	 * Check whether order is placed.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isPlaced( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_PLACED;
		}

		return $this->status >= self::STATUS_PLACED;
	}

	/**
	 * Check whether order is cancelled.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isCancelled( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_CANCELLED;
		}

		return $this->status >= self::STATUS_CANCELLED;
	}

	/**
	 * Check whether order is paid.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isPaid( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_PAID;
		}

		return $this->status >= self::STATUS_PAID;
	}

	/**
	 * Check whether order is refunded.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isRefunded( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_REFUNDED;
		}

		return $this->status >= self::STATUS_REFUNDED;
	}

	/**
	 * Check whether order is confirmed.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isConfirmed( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_CONFIRMED;
		}

		return $this->status >= self::STATUS_CONFIRMED;
	}

	/**
	 * Check whether order is processed.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isProcessed( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_PROCESSED;
		}

		return $this->status >= self::STATUS_PROCESSED;
	}

	/**
	 * Check whether order is shipped.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isShipped( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_SHIPPED;
		}

		return $this->status >= self::STATUS_SHIPPED;
	}

	/**
	 * Check whether order is delivered.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isDelivered( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_DELIVERED;
		}

		return $this->status >= self::STATUS_DELIVERED;
	}

	/**
	 * Check whether order is returned.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isReturned( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_RETURNED;
		}

		return $this->status >= self::STATUS_RETURNED;
	}

	/**
	 * Check whether order is under dispute.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isDispute( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_DISPUTE;
		}

		return $this->status >= self::STATUS_DISPUTE;
	}

	/**
	 * Check whether order is completed.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isCompleted( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_COMPLETED;
		}

		return $this->status >= self::STATUS_COMPLETED;
	}

	/**
	 * Check whether order is printable.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isPrintable( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_COMPLETED;
		}

		return in_array( $this->status, [ self::STATUS_PAID, self::STATUS_DELIVERED, self::STATUS_COMPLETED ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_ORDER );
	}

	// CMG parent classes --------------------

	// Order ---------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Use only if title is unique for order.
	 *
	 * @param string $title
	 * @return Order
	 */
	public static function findByTitle( $title ) {

		return self::find()->where( 'title=:title', [ ':title' => $title ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

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
use cmsgears\payment\common\config\PaymentGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\mappers\IAddress;

use cmsgears\payment\common\models\base\PaymentTables;
use cmsgears\payment\common\models\resources\Transaction;
use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Order represents order either placed by user or created as part of an order specific process.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $baseId
 * @property integer $cartId
 * @property integer $userId
 * @property integer $voucherId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property string $code
 * @property string $service
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property float $subTotal
 * @property float $tax1
 * @property float $tax2
 * @property float $tax3
 * @property float $tax4
 * @property float $tax5
 * @property float $charge1
 * @property float $charge2
 * @property float $charge3
 * @property float $charge4
 * @property float $charge5
 * @property float $shipping
 * @property float $total
 * @property float $discount
 * @property float $grandTotal
 * @property string $currency
 * @property boolean $shipToBilling
 * @property string $token
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
class Order extends \cmsgears\core\common\models\base\ModelResource implements IAddress, IAuthor,
	IContent, IData, IGridCache, IMultiSite, IOwner {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	/**
	 * Order is created
	 */
	const STATUS_NEW		=	  0;

	/**
	 * Order need approval
	 */
	const STATUS_APPROVED	=  1000;

	/**
	 * Order is placed
	 */
	const STATUS_PLACED		=  2000;

	/**
	 * Order on hold
	 */
    const STATUS_HOLD       =  2500;

	/**
	 * Order rejected - most probably by the vendor or site admin
	 */
	const STATUS_REJECTED	=  2600;

	/**
	 * Order cancelled - most probably by the customer(no money return if paid) or vendor(money return if paid)
	 */
	const STATUS_CANCELLED	=  3000;

	/**
	 * Payment is failed
	 */
	const STATUS_FAILED		=  3500;

	/**
	 * Complete payment is done
	 */
	const STATUS_PAID		=  4000;

	/**
	 * Payment confirmed by the vendor
	 */
	const STATUS_CONFIRMED	=  5000;

	/**
	 * Order refunded - money returned - mutually agreed by the customer and the vendor
	 * Order might not be delivered by the vendor or returned by the customer(damaged or no satisfaction)
	 */
	const STATUS_REFUNDED	=  6000;

	/**
	 * Order processed by the vendor and may be ready for the shipment if required
	 */
	const STATUS_PROCESSED	=  7000;

	/**
	 * Order shipped by the vendor after either paid or confirmed or processed
	 */
	const STATUS_SHIPPED	=  8000;

	/**
	 * Order delivered by the vendor
	 */
	const STATUS_DELIVERED	=  9000;

	/**
	 * Order returned to the vendor - damaged, no satisfaction, no receiver
	 */
	const STATUS_RETURNED	= 10000;

	/**
	 * Order in dispute - useful in case of multi-vendor systems - the system admin will resolve the dispute
	 */
	const STATUS_DISPUTE	= 11000;

	/**
	 * Order signed and received by the customer, fulfilled by the vendor and customer
	 */
	const STATUS_COMPLETED	= 12000;

	// Public -----------------

	public static $statusMap = [
		self::STATUS_NEW  => 'New',
		self::STATUS_APPROVED => 'Approved',
		self::STATUS_PLACED => 'Placed',
        self::STATUS_HOLD => 'Hold',
		self::STATUS_REJECTED => 'Rejected',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_FAILED => 'Failed',
		self::STATUS_PAID => 'Paid',
		self::STATUS_CONFIRMED => 'Confirmed',
		self::STATUS_REFUNDED => 'Refunded',
		self::STATUS_PROCESSED => 'Processed',
		self::STATUS_SHIPPED => 'Shipped',
		self::STATUS_DELIVERED => 'Delivered',
		self::STATUS_RETURNED => 'Returned',
		self::STATUS_DISPUTE => 'Dispute',
		self::STATUS_COMPLETED => 'Completed'
	];

	// Used for external docs
	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Approved' => self::STATUS_APPROVED,
		'Placed' => self::STATUS_PLACED,
        'Hold' => self::STATUS_HOLD,
		'Rejected' => self::STATUS_REJECTED,
		'Cancelled' => self::STATUS_CANCELLED,
		'Failed' => self::STATUS_FAILED,
		'Paid' => self::STATUS_PAID,
		'Confirmed' => self::STATUS_CONFIRMED,
		'Refunded' => self::STATUS_REFUNDED,
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
		'placed' => self::STATUS_PLACED,
        'hold' => self::STATUS_HOLD,
		'rejected' => self::STATUS_REJECTED,
		'cancelled' => self::STATUS_CANCELLED,
		'failed' => self::STATUS_FAILED,
		'paid' => self::STATUS_PAID,
		'confirmed' => self::STATUS_CONFIRMED,
		'refunded' => self::STATUS_REFUNDED,
		'processed' => self::STATUS_PROCESSED,
		'shipped' => self::STATUS_SHIPPED,
		'delivered' => self::STATUS_DELIVERED,
		'returned' => self::STATUS_RETURNED,
		'dispute' => self::STATUS_DISPUTE,
		'completed' => self::STATUS_COMPLETED
	];

	public static $filterStatusMap = [
		'new' => 'New',
		'approved' => 'Approved',
		'placed' => 'Placed',
        'hold' => 'Hold',
		'rejected' => 'Rejected',
		'cancelled' => 'Cancelled',
		'failed' => 'Failed',
		'paid' => 'Paid',
		'confirmed' => 'Confirmed',
		'refunded' => 'Refunded',
		'processed' => 'Processed',
		'shipped' => 'Shipped',
		'delivered' => 'Delivered',
		'returned' => 'Returned',
		'dispute' => 'Dispute',
		'completed' => 'Completed'
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CartGlobal::TYPE_ORDER;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AddressTrait;
	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use OwnerTrait;

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
			[ [ 'id', 'content' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type', 'currency', 'service', 'token' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'code', 'title' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'subTotal', 'shipping', 'total', 'discount', 'grandTotal' ], 'number', 'min' => 0 ],
			[ [ 'tax1', 'tax2', 'tax3', 'tax4', 'tax5' ], 'number', 'min' => 0 ],
			[ [ 'charge1', 'charge2', 'charge3', 'charge4', 'charge5' ], 'number', 'min' => 0 ],
			[ [ 'shipToBilling', 'gridCacheValid' ], 'boolean' ],
			[ [ 'siteId', 'baseId', 'cartId', 'userId', 'voucherId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'eta', 'deliveredAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'baseId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PARENT_ORDER ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'service' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SERVICE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'subTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_SUB ),
			'tax1' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax2' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax3' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax4' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax5' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'charge1' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CHARGE ),
			'charge2' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CHARGE ),
			'charge3' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CHARGE ),
			'charge4' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CHARGE ),
			'charge5' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CHARGE ),
			'shipping' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIPPING ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'grandTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_GRAND ),
			'currency' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_CURRENCY ),
			'shipToBilling' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIP_TO_BILLING ),
			'token' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TOKEN ),
			'eta' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_ESTIMATED_DELIVERY ),
			'deliveredAt' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DELIVERY_DATE ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			// Default Site
			if( empty( $this->siteId ) || $this->siteId <= 0 ) {

				$this->siteId = Yii::$app->core->siteId;
			}

			// Default User
			if( empty( $this->userId ) || $this->userId <= 0 ) {

				$this->userId = null;
			}

			// Default Type
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

	        return true;
	    }

		return false;
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

		return $this->hasOne( Cart::class, [ 'id' => 'cartId' ] );
	}

	public function getVoucher() {

		return $this->hasOne( Voucher::class, [ 'id' => 'voucherId' ] );
	}

	/**
	 * Returns the successful transaction associated with the order.
	 *
	 * It's useful in the cases where only one transaction is required for an order, like virtual goods.
	 *
	 * @return Transaction
	 */
	public function getTransaction() {

		$transactionTable = PaymentTables::getTableName( PaymentTables::TABLE_TRANSACTION );

		$success	= Transaction::STATUS_SUCCESS;
		$credit		= Transaction::TYPE_CREDIT;

		return $this->hasOne( Transaction::class, [ 'orderId' => 'id' ] )
			->where( "$transactionTable.status=$success AND $transactionTable.type=$credit" );
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

		$this->title = Yii::$app->security->generateRandomString();
	}

	/**
	 * Returns string representation of the status.
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
	 * Check whether order is on hold.
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
	 * Check whether order is rejected.
	 *
	 * $param boolean $strict
	 * @return boolean
	 */
	public function isRejected() {

		return $this->status == self::STATUS_REJECTED;
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
	 * Check whether order can be cancelled.
	 *
	 * @return boolean
	 */
	public function isCancellable() {

		return !$this->isPaid( false ) && !$this->isCancelled();
	}

	/**
	 * Check whether order is cancelled.
	 *
	 * @return boolean
	 */
	public function isCancelled() {

		return $this->status == self::STATUS_CANCELLED;
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

	public function getDisplayName() {

		return $this->title;
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryByUserId( $userId ) {

		return static::find()->where( 'userId=:uid', [ ':uid' => $userId ] );
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

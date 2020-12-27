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
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\mappers\IAddress;

use cmsgears\core\common\models\entities\User;
use cmsgears\payment\common\models\base\PaymentTables;
use cmsgears\payment\common\models\resources\Transaction;
use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Invoice represents order either placed by user or created as part of an order specific process.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $orderId
 * @property integer $userId
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
 * @property float $tax
 * @property float $shipping
 * @property float $total
 * @property float $discount
 * @property float $grandTotal
 * @property string $currency
 * @property string $token
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property date $issueDate
 * @property date $dueDate
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Invoice extends \cmsgears\core\common\models\base\ModelResource implements IAddress, IAuthor,
	IContent, IData, IGridCache, IMultiSite {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	/**
	 * Invoice is created
	 */
	const STATUS_NEW		=	  0;

	/**
	 * Invoice need approval
	 */
	const STATUS_APPROVED	=  1000;

	/**
	 * Invoice on hold
	 */
    const STATUS_HOLD       =  2500;

	/**
	 * Invoice cancelled
	 */
	const STATUS_CANCELLED	=  3000;

	/**
	 * Complete payment is done
	 */
	const STATUS_PAID		=  4000;

	/**
	 * Payment confirmed by the vendor
	 */
	const STATUS_CONFIRMED	=  6000;

	/**
	 * Invoice refunded - money returned - mutually agreed by the customer and the vendor
	 */
	const STATUS_REFUNDED	=  5000;

	/**
	 * Invoice signed and received by the customer, fulfilled by the vendor and customer
	 */
	const STATUS_COMPLETED	= 12000;

	// Public -----------------

	public static $statusMap = [
		self::STATUS_NEW  => 'New',
		self::STATUS_APPROVED => 'Approved',
        self::STATUS_HOLD => 'Hold',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_PAID => 'Paid',
		self::STATUS_CONFIRMED => 'Confirmed',
		self::STATUS_REFUNDED => 'Refunded',
		self::STATUS_COMPLETED => 'Completed'
	];

	// Used for external docs
	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Approved' => self::STATUS_APPROVED,
        'Hold' => self::STATUS_HOLD,
		'Cancelled' => self::STATUS_CANCELLED,
		'Paid' => self::STATUS_PAID,
		'Confirmed' => self::STATUS_CONFIRMED,
		'Refunded' => self::STATUS_REFUNDED,
		'Completed'  => self::STATUS_COMPLETED
	];

	// Used for url params
	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'approved' => self::STATUS_APPROVED,
        'hold' => self::STATUS_HOLD,
		'cancelled' => self::STATUS_CANCELLED,
		'paid' => self::STATUS_PAID,
		'confirmed' => self::STATUS_CONFIRMED,
		'refunded' => self::STATUS_REFUNDED,
		'completed' => self::STATUS_COMPLETED
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CartGlobal::TYPE_INVOICE;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AddressTrait;
	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;

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
			[ [ 'parentType', 'type', 'currency', 'service' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'code', 'title' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'subTotal', 'tax', 'shipping', 'total', 'discount', 'grandTotal' ], 'number', 'min' => 0 ],
			[ [ 'gridCacheValid' ], 'boolean' ],
			[ [ 'siteId', 'orderId', 'userId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'issueDate', 'dueDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ],
			[ 'dueDate', 'compareDate', 'compareAttribute' => 'issueDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'Due date must be greater than or equal to issue date.' ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'orderId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_ORDER ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'service' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SERVICE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'subTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_SUB ),
			'tax' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'shipping' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIPPING ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'grandTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_GRAND ),
			'currency' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_CURRENCY ),
			'issueDate' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DATE_ISSUED ),
			'dueDate' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DATE_DUE ),
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

			// Default Type
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Invoice -------------------------------

	public function getOrder() {

		return $this->hasOne( Order::class, [ 'id' => 'orderId' ] );
	}

	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	/**
	 * Returns the successful transaction associated with the invoice.
	 *
	 * It's useful in the cases where only one transaction is required for an invoice, like virtual goods.
	 *
	 * @return Transaction
	 */
	public function getTransaction() {

		$transactionTable = PaymentTables::getTableName( PaymentTables::TABLE_TRANSACTION );

		$success	= Transaction::STATUS_SUCCESS;
		$credit		= Transaction::TYPE_CREDIT;

		return $this->hasOne( Transaction::class, [ 'invoiceId' => 'id' ] )
			->where( "$transactionTable.status=$success AND $transactionTable.type=$credit" );
	}

	/**
	 * Returns all the transactions associated with the invoice.
	 *
	 * @return Transaction[]
	 */
	public function getTransactions() {

		return $this->hasMany( Transaction::class, [ 'orderId' => 'id' ] );
	}

	/**
	 * Returns the items associated with the invoice.
	 *
	 * @return InvoiceItem[]
	 */
	public function getItems() {

		return $this->hasMany( InvoiceItem::class, [ 'invoiceId' => 'id' ] );
	}

	/**
	 * Generate and set the title of invoice.
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
	 * Check whether invoice is new.
	 *
	 * @return boolean
	 */
	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	/**
	 * Check whether invoice is on hold.
	 *
	 * @return boolean
	 */
	public function isHold() {

		return $this->status == self::STATUS_HOLD;
	}

	/**
	 * Check whether invoice is approved.
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
	 * Check whether invoice is cancelled.
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
	 * Check whether invoice is paid.
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
	 * Check whether invoice is confirmed.
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
	 * Check whether invoice is refunded.
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
	 * Check whether invoice is completed.
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

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_INVOICE );
	}

	// CMG parent classes --------------------

	// Invoice ---------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryByUserId( $userId ) {

		return static::find()->where( 'userId=:uid', [ ':uid' => $userId ] );
	}

	// Read - Find ------------

	/**
	 * Use only if title is unique for invoice.
	 *
	 * @param string $title
	 * @return Invoice
	 */
	public static function findByTitle( $title ) {

		return self::find()->where( 'title=:title', [ ':title' => $title ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

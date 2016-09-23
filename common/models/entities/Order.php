<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\payment\common\models\entities\Transaction;
use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Order Entity - The primary class.
 *
 * @property integer $id
 * @property integer $baseId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property integer $subTotal
 * @property integer $tax
 * @property integer $shipping
 * @property integer $total
 * @property integer $discount
 * @property integer $grandTotal
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $eta
 * @property datetime $deliveredAt
 */
class Order extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_NEW				=	 0;
	const STATUS_CONFIRMED			= 1000;
	const STATUS_CANCELLED			= 2000;
	const STATUS_PLACED				= 3000;
	const STATUS_PAID				= 4000;
	const STATUS_DELIVERED			= 5000;
	const STATUS_RETURNED			= 6000;

	// Public -----------------

	public static $statusMap = array(
		self::STATUS_NEW  => 'New',
		self::STATUS_CONFIRMED => 'Confirmed',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_PLACED => 'Placed',
		self::STATUS_PAID => 'Paid',
		self::STATUS_DELIVERED => 'Delivered',
		self::STATUS_RETURNED => 'Returned'
		);

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $addressType	= CartGlobal::TYPE_ORDER;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use AddressTrait;
	use CreateModifyTrait;
	use ResourceTrait;

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
				'class' => AuthorBehavior::className()
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::className(),
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

		return [
			[ 'title', 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'title', 'description'], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'subTotal', 'tax', 'shipping', 'total', 'discount', 'grandTotal' ], 'number', 'min' => 0 ],
			[ [ 'baseId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'eta', 'deliveredAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];
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
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'subTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_SUB ),
			'tax' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'shipping' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIPPING ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'grandTotal' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL_GRAND ),
			'deliveryDate' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DELIVERY_DATE ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Order ---------------------------------

	public function getParentOrder() {

		return $this->hasOne( Order::className(), [ 'id' => 'baseId' ] );
	}

	public function getPayment() {

		return $this->hasOne( Transaction::className(), [ 'parentId' => 'id' ] );
	}

	public function getChildOrders() {

		return $this->hasMany( Order::className(), [ 'baseId' => 'id' ] );
	}

	public function getItems() {

		return $this->hasMany( OrderItem::className(), [ 'orderId' => 'id' ] );
	}

	public function generateName() {

		$this->title = Yii::$app->security->generateRandomString( 16 );
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isConfirmed() {

		return $this->status == self::STATUS_CONFIRMED;
	}

	public function isCancelled() {

		return $this->status == self::STATUS_CANCELLED;
	}

	public function isPlaced() {

		return $this->status == self::STATUS_PLACED;
	}

	public function isPaid() {

		return $this->status == self::STATUS_PAID;
	}

	public function isDelivered() {

		return $this->status == self::STATUS_DELIVERED;
	}

	public function isReturned() {

		return $this->status == self::STATUS_RETURNED;
	}

	public function isPrintable() {

		return in_array( $this->status, [ self::STATUS_PAID, self::STATUS_DELIVERED ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_ORDER;
	}

	// CMG parent classes --------------------

	// Order ---------------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	public static function findByTitle( $title ) {

		return self::find()->where( 'title=:title', [ ':title' => $title ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

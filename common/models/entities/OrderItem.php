<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Option;
use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * OrderItem Entity - The primary class.
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $purchasingUnitId
 * @property integer $quantityUnitId
 * @property integer $weightUnitId
 * @property integer $volumeUnitId
 * @property integer $lengthUnitId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property integer $name
 * @property integer $sku
 * @property integer $price
 * @property integer $discount
 * @property integer $purchase
 * @property integer $quantity
 * @property integer $weight
 * @property integer $volume
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $radius
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */
class OrderItem extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

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
			[ [ 'orderId', 'name', 'price', 'purchase' ], 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'sku' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'price', 'discount', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius' ], 'number', 'min' => 0 ],
			[ [ 'orderId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'orderId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_ORDER ),
			'purchasingUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_PURCHASING ),
			'quantityUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_QUANTITY ),
			'weightUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_WEIGHT ),
			'volumeUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_VOLUME ),
			'lengthUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_LENGTH ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'sku' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SKU ),
			'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'purchase' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'quantity' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
			'weight' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_WEIGHT ),
			'volume' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_VOLUME ),
			'length' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_LENGTH ),
			'width' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_WIDTH ),
			'height' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_HEIGHT ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// OrderItem -----------------------------

	public function getOrder() {

		return $this->hasOne( Order::className(), [ 'id' => 'orderId' ] );
	}

	public function getPurchasingUnit() {

		return $this->hasOne( Option::className(), [ 'id' => 'purchasingUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as pUnit' );
	}

	public function getQuantityUnit() {

		return $this->hasOne( Option::className(), [ 'id' => 'quantityUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as qUnit' );
	}

	public function getWeightUnit() {

		return $this->hasOne( Option::className(), [ 'id' => 'weightUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as wUnit' );
	}

	public function getVolumeUnit() {

		return $this->hasOne( Option::className(), [ 'id' => 'volumeUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as vUnit' );
	}

	public function getLengthUnit() {

		return $this->hasOne( Option::className(), [ 'id' => 'lengthUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as lUnit' );
	}

	public function getTotalPrice() {

		$price	= $this->purchase * $this->price;

		return round( $price, 2 );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_ORDER_ITEM;
	}

	// CMG parent classes --------------------

	// OrderItem -----------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'order', 'purchasingUnit', 'quantityUnit', 'weightUnit', 'volumeUnit', 'lengthUnit', 'creator' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryByOrderId( $orderId ) {

		return self::find()->where( 'orderId=:oid', [ ':oid' => $orderId ] );
	}

	// Read - Find ------------

	public static function findByOrderId( $oderId ) {

		return self::queryByOrderId( $oderId )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

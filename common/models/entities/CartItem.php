<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\Uom;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * CartItem Entity - The primary class.
 *
 * @property integer $id
 * @property integer $cartId
 * @property integer $primaryUnitId
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
 * @property integer $total
 * @property integer $primary
 * @property integer $purchase
 * @property integer $quantity
 * @property integer $weight
 * @property integer $volume
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $radius
 * @property boolean $keep
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */
class CartItem extends \cmsgears\core\common\models\base\Entity {

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
			// Required, Safe
			[ [ 'name', 'price', 'purchase' ], 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'sku' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			// Other
			[ [ 'price', 'discount', 'total', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius' ], 'number', 'min' => 0 ],
			[ 'keep', 'boolean' ],
			[ [ 'parentId', 'parentType', 'cartId' ], 'unique', 'targetAttribute' => [ 'parentId', 'parentType', 'cartId' ] ],
			[ [ 'cartId', 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'cartId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CART ),
			'primaryUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_PRIMARY ),
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
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'purchase' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PURCHASE ),
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

	// CartItem ------------------------------

	public function getCart() {

		return $this->hasOne( Cart::className(), [ 'id' => 'cartId' ] );
	}

	public function getPrimaryUnit() {

		return $this->hasOne( Uom::className(), [ 'id' => 'primaryUnitId' ] )->from( CartTables::TABLE_UOM . ' as primaryUnit' );
	}

	public function getPurchasingUnit() {

		return $this->hasOne( Uom::className(), [ 'id' => 'purchasingUnitId' ] )->from( CartTables::TABLE_UOM . ' as purchasingUnit' );
	}

	public function getQuantityUnit() {

		return $this->hasOne( Uom::className(), [ 'id' => 'quantityUnitId' ] )->from( CartTables::TABLE_UOM . ' as quantityUnit' );
	}

	public function getWeightUnit() {

		return $this->hasOne( Uom::className(), [ 'id' => 'weightUnitId' ] )->from( CartTables::TABLE_UOM . ' as weightUnit' );
	}

	public function getVolumeUnit() {

		return $this->hasOne( Uom::className(), [ 'id' => 'lengthUnitId' ] )->from( CartTables::TABLE_UOM . ' as volumeUnit' );
	}

	public function getLengthUnit() {

		return $this->hasOne( Uom::className(), [ 'id' => 'lengthUnitId' ] )->from( CartTables::TABLE_UOM . ' as lengthUnit' );
	}

	public function getTotalPrice( $precision = 2 ) {

		$price	= $this->purchase * $this->price;

		return round( $price, $precision );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_CART_ITEM;
	}

	// CMG parent classes --------------------

	// CartItem ------------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'cart', 'primaryUnit', 'purchasingUnit', 'quantityUnit', 'weightUnit', 'volumeUnit', 'lengthUnit', 'creator' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryByCartId( $cartId ) {

		return self::find()->where( 'cartId=:cid', [ ':cid' => $cartId ] );
	}

	// Read - Find ------------

	public static function findByCartId( $cartId ) {

		return self::queryByCartId( $cartId )->all();
	}

	public static function findByParentCartId( $parentId, $parentType, $cartId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND cartId=:cid', [ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $cartId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	public static function deleteByCartId( $cartId ) {

		self::deleteAll( 'cartId=:id', [ ':id' => $cartId ] );
	}
}

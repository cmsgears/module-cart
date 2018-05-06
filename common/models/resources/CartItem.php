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
use cmsgears\core\common\models\interfaces\base\IModelResource;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\base\Entity;
use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\Uom;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\ModelResourceTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * CartItem stores the items added to the cart. These can be either active or deselected
 * by the user.
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
 * @property float $price
 * @property float $discount
 * @property float $total
 * @property float $primary
 * @property float $purchase
 * @property float $quantity
 * @property float $weight
 * @property float $volume
 * @property float $length
 * @property float $width
 * @property float $height
 * @property float $radius
 * @property boolean $keep
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class CartItem extends Entity implements IAuthor, IModelResource, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CartGlobal::TYPE_CART_ITEM;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use GridCacheTrait;
	use ModelResourceTrait;

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
			[ [ 'name', 'price', 'purchase' ], 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Unique
			[ [ 'parentId', 'parentType', 'cartId' ], 'unique', 'targetAttribute' => [ 'parentId', 'parentType', 'cartId' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'sku' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'price', 'discount', 'total', 'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius' ], 'number', 'min' => 0 ],
			[ [ 'keep', 'gridCacheValid' ], 'boolean' ],
			[ [ 'cartId', 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
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
			'primary' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QTY_PRIMARY ),
			'purchase' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QTY_PURCHASE ),
			'quantity' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
			'weight' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_WEIGHT ),
			'volume' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_VOLUME ),
			'length' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_LENGTH ),
			'width' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_WIDTH ),
			'height' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_HEIGHT ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// CartItem ------------------------------

	/**
	 * Returns the cart associated with the item.
	 *
	 * @return Cart
	 */
	public function getCart() {

		return $this->hasOne( Cart::class, [ 'id' => 'cartId' ] );
	}

	/**
	 * Returns primary unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getPrimaryUnit() {

		return $this->hasOne( Uom::class, [ 'id' => 'primaryUnitId' ] )->from( CartTables::TABLE_UOM . ' as primaryUnit' );
	}

	/**
	 * Returns purchasing unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getPurchasingUnit() {

		return $this->hasOne( Uom::class, [ 'id' => 'purchasingUnitId' ] )->from( CartTables::TABLE_UOM . ' as purchasingUnit' );
	}

	/**
	 * Returns quantity unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getQuantityUnit() {

		return $this->hasOne( Uom::class, [ 'id' => 'quantityUnitId' ] )->from( CartTables::TABLE_UOM . ' as quantityUnit' );
	}

	/**
	 * Returns weight unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getWeightUnit() {

		return $this->hasOne( Uom::class, [ 'id' => 'weightUnitId' ] )->from( CartTables::TABLE_UOM . ' as weightUnit' );
	}

	/**
	 * Returns volume unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getVolumeUnit() {

		return $this->hasOne( Uom::class, [ 'id' => 'lengthUnitId' ] )->from( CartTables::TABLE_UOM . ' as volumeUnit' );
	}

	/**
	 * Returns length unit associated with the item. It will be used for length, width, height and radius.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getLengthUnit() {

		return $this->hasOne( Uom::class, [ 'id' => 'lengthUnitId' ] )->from( CartTables::TABLE_UOM . ' as lengthUnit' );
	}

	/**
	 * Returns the total price of the item.
	 *
	 * Total Price = ( Unit Price - Unit Discount ) * Purchasing Quantity
	 *
	 * @param type $precision
	 * @return type
	 */
	public function getTotalPrice( $precision = 2 ) {

		$price	= ( $this->price - $this->discount ) * $this->purchase;

		return round( $price, $precision );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_CART_ITEM );
	}

	// CMG parent classes --------------------

	// CartItem ------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'cart', 'primaryUnit', 'purchasingUnit', 'quantityUnit', 'weightUnit', 'volumeUnit', 'lengthUnit', 'creator' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the cart item by cart id.
	 *
	 * @param integer $cartId
	 * @return \yii\db\ActiveQuery to query by cart id.
	 */
	public static function queryByCartId( $cartId ) {

		return self::find()->where( 'cartId=:cid', [ ':cid' => $cartId ] );
	}

	// Read - Find ------------

	/**
	 * Return the cart items associated with given cart id.
	 *
	 * @param integer $cartId
	 * @return CartItem[]
	 */
	public static function findByCartId( $cartId ) {

		return self::queryByCartId( $cartId )->all();
	}

	/**
	 * Return the cart item associated with given parent id, parent type and cart id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $cartId
	 * @return CartItem
	 */
	public static function findByParentCartId( $parentId, $parentType, $cartId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND cartId=:cid', [ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $cartId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete cart items associated with given cart id.
	 *
	 * @param integer $cartId
	 * @return integer Number of rows.
	 */
	public static function deleteByCartId( $cartId ) {

		return self::deleteAll( 'cartId=:id', [ ':id' => $cartId ] );
	}

}

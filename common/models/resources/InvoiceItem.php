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
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\Uom;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * InvoiceItem stores the items associated with invoice.
 *
 * @property integer $id
 * @property integer $invoiceId
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
 * @property string $sku
 * @property integer $status
 * @property float $price
 * @property float $discount
 * @property float $tax1
 * @property float $tax2
 * @property float $tax3
 * @property float $tax4
 * @property float $tax5
 * @property float $total
 * @property integer $primary
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
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class InvoiceItem extends \cmsgears\core\common\models\base\ModelResource implements IAuthor,
	IContent, IData, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_NEW		=   0;
	const STATUS_CANCELLED	= 100;
	const STATUS_RETURNED	= 200;
	const STATUS_RECEIVED	= 500;

	// Public -----------------

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_RETURNED => 'Returned',
		self::STATUS_RECEIVED => 'Received'
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CartGlobal::TYPE_INVOICE_ITEM;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
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
			[ [ 'invoiceId', 'name', 'price', 'purchase' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Unique
			[ 'invoiceId', 'unique', 'targetAttribute' => [ 'parentId', 'parentType', 'invoiceId' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'sku' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'price', 'discount', 'primary', 'purchase', 'quantity', 'total', 'weight', 'volume', 'length', 'width', 'height', 'radius' ], 'number', 'min' => 0 ],
			[ [ 'tax1', 'tax2', 'tax3', 'tax4', 'tax5' ], 'number', 'min' => 0 ],
			[ 'status', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'gridCacheValid', 'boolean' ],
			[ [ 'invoiceId', 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'invoiceId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_INVOICE ),
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
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'tax1' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax2' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax3' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax4' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'tax5' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX ),
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

	// InvoiceItem ---------------------------

	/**
	 * Returns the invoice associated with the item.
	 *
	 * @return Order
	 */
	public function getInvoice() {

		return $this->hasOne( Invoice::class, [ 'id' => 'invoiceId' ] );
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

		$price = ( $this->price - $this->discount ) * $this->purchase;

		return round( $price, $precision );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_INVOICE_ITEM );
	}

	// CMG parent classes --------------------

	// InvoiceItem -----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'invoice', 'purchasingUnit', 'creator' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithUoms( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'invoice', 'primaryUnit', 'purchasingUnit', 'quantityUnit', 'weightUnit', 'volumeUnit', 'lengthUnit', 'creator' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the invoice item by invoice id.
	 *
	 * @param integer $invoiceId
	 * @return \yii\db\ActiveQuery to query by invoice id.
	 */
	public static function queryByInvoiceId( $invoiceId ) {

		return self::find()->where( 'orderId=:oid', [ ':oid' => $invoiceId ] );
	}

	// Read - Find ------------

	/**
	 * Return the order items associated with given invoice id.
	 *
	 * @param integer $invoiceId
	 * @return InvoiceItem[]
	 */
	public static function findByInvoiceId( $invoiceId ) {

		return self::queryByInvoiceId( $invoiceId )->all();
	}

	/**
	 * Return the order item associated with given parent id, parent type and order id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $invoiceId
	 * @return InvoiceItem
	 */
	public static function findByParentInvoiceId( $parentId, $parentType, $invoiceId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND invoiceId=:iid', [ ':pid' => $parentId, ':ptype' => $parentType, ':iid' => $invoiceId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete order items associated with given order id.
	 *
	 * @param integer $invoiceId
	 * @return integer Number of rows.
	 */
	public static function deleteByInvoiceId( $invoiceId ) {

		return self::deleteAll( 'invoiceId=:id', [ ':id' => $invoiceId ] );
	}

}

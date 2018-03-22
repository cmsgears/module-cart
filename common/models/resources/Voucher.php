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
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IModelResource;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\base\Entity;
use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\ModelResourceTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Voucher are discount code applied while checkout either on cart or product.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $code
 * @property integer $amount
 * @property integer $taxType
 * @property boolean $freeShipping
 * @property float $minPurchase
 * @property float $maxDiscount
 * @property datetime $startTime
 * @property datetime $endTime
 * @property short $usageLimit
 * @property short $usageCount
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
class Voucher extends Entity implements IAuthor, IModelResource, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_CART				=   0;
	const TYPE_CART_PERCENT		= 100;
	const TYPE_PRODUCT			= 200;
	const TYPE_PRODUCT_PERCENT	= 300;

	const TAX_BEFORE_DISCOUNT	=   0;
	const TAX_AFTER_DISCOUNT	= 100;

	// Public -----------------

	public static $typesMap = [
		self::TYPE_CART	 => 'Cart $',
		self::TYPE_CART_PERCENT => 'Cart %',
		self::TYPE_PRODUCT => 'Product $',
		self::TYPE_PRODUCT_PERCENT => 'Product %'
	];

	public static $typesMapRev = [
		'Cart $' => self::TYPE_CART,
		'Cart %' => self::TYPE_CART_PERCENT,
		'Product $' => self::TYPE_PRODUCT,
		'Product %' => self::TYPE_PRODUCT_PERCENT
	];

	public static $taxTypeMap = [
		self::TAX_BEFORE_DISCOUNT  => 'Before discount',
		self::TAX_AFTER_DISCOUNT => 'After discount'
	];

	public static $taxTypeMapRev = [
		'Before discount' => self::TAX_BEFORE_DISCOUNT,
		'After discount' => self::TAX_AFTER_DISCOUNT
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CartGlobal::TYPE_VOUCHER;

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
			[ [ 'type', 'name', 'amount' ], 'required' ],
			[ [ 'id', 'description', 'content', 'data', 'gridCache' ], 'safe' ],
			// Unique
			[ 'code', 'unique', 'targetAttribute' => 'code' ],
			// Text Limit
			[ [ 'parentType', 'code' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'type', 'taxType', 'usageLimit', 'usageCount' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'freeShipping', 'gridCacheValid' ], 'boolean' ],
			[ [ 'amount', 'minPurchase', 'maxDiscount' ], 'number', 'min' => 0 ],
			[ [ 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'startTime', 'endTime', 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'description', 'code' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'amount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_AMOUNT ),
			'taxType' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX_TYPE ),
			'freeShipping' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIPPING_FREE ),
			'minPurchase' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_MIN_PURCHASE ),
			'maxDiscount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_MAX_DISCOUNT ),
			'startTime' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TIME_START ),
			'endTime' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TIME_END ),
			'usageLimit' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_USAGE_LIMIT ),
			'usageCount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_USAGE_COUNT ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Voucher -------------------------------

	/**
	 * Generate and set the code of voucher.
	 *
	 * @return void
	 */
	public function generateCode() {

		$this->code = Yii::$app->security->generateRandomString( 8 );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_VOUCHER );
	}

	// CMG parent classes --------------------

	// Voucher -------------------------------

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

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

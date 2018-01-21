<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Voucher Entity - The primary class.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property string $name
 * @property string $description
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
 */
class Voucher extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_CART				= 'cart$';
	const TYPE_CART_PERCENT		= 'cart%';
	const TYPE_PRODUCT			= 'product$';
	const TYPE_PRODUCT_PERCENT	= 'product%';

	const TAX_BEFORE_DISCOUNT	=  0;
	const TAX_AFTER_DISCOUNT	= 10;

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

	public $modelType = CartGlobal::TYPE_VOUCHER;

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
			]
		];
	}

	// yii\base\Model ---------

	public function rules() {

		$rules = [
			// Required, Safe
			[ [ 'type', 'name', 'amount' ], 'required' ],
			[ [ 'id', 'description' ], 'safe' ],
			// Unique
			[ [ 'name', 'type' ], 'unique', 'targetAttribute' => [ 'name', 'type' ] ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'taxType', 'usageLimit', 'usageCount' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'freeShipping', 'boolean' ],
			[ [ 'amount', 'minPurchase', 'maxDiscount' ], 'number', 'min' => 0 ],
			[ [ 'startTime', 'endTime', 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'amount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_AMOUNT ),
			'taxType' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX_TYPE ),
			'freeShipping' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIPPING_FREE ),
			'minPurchase' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_MIN_PURCHASE ),
			'maxDiscount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_MAX_DISCOUNT ),
			'usageLimit' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_USAGE_LIMIT ),
			'usageCount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_USAGE_COUNT ),
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Voucher -------------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_VOUCHER;
	}

	// CMG parent classes --------------------

	// Voucher -------------------------------

	// Read - Query -----------

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

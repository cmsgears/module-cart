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
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IVisual;

use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Voucher are discount code applied while checkout either on cart or product.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $bannerId
 * @property integer $mbannerId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $type
 * @property integer $scheme
 * @property string $code
 * @property integer $amount
 * @property integer $taxType
 * @property boolean $freeShipping
 * @property float $minPurchase
 * @property float $maxDiscount
 * @property datetime $startsAt
 * @property datetime $endsAt
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
class Voucher extends \cmsgears\core\common\models\base\ModelResource implements IAuthor,
	IContent, IData, IGridCache, IMultiSite, IOwner, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const SCHEME_CART				=   0;
	const SCHEME_CART_PERCENT		= 100;
	const SCHEME_PRODUCT			= 200;
	const SCHEME_PRODUCT_PERCENT	= 300;

	const STATUS_NEW		=   0;
	const STATUS_ACTIVE		= 100;
	const STATUS_BLOCKED	= 200;

	const TAX_BEFORE_DISCOUNT	=   0;
	const TAX_AFTER_DISCOUNT	= 100;

	// Public -----------------

	public static $schemeMap = [
		self::SCHEME_CART => 'Cart $',
		self::SCHEME_CART_PERCENT => 'Cart %',
		self::SCHEME_PRODUCT => 'Product $',
		self::SCHEME_PRODUCT_PERCENT => 'Product %'
	];

	public static $revSchemeMap = [
		'Cart $' => self::SCHEME_CART,
		'Cart %' => self::SCHEME_CART_PERCENT,
		'Product $' => self::SCHEME_PRODUCT,
		'Product %' => self::SCHEME_PRODUCT_PERCENT
	];

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_BLOCKED => 'Blocked'
	];

	public $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'active' => self::STATUS_ACTIVE,
		'blocked' => self::STATUS_BLOCKED
	];

	public $filterStatusMap = [
		'new' => 'New',
		'active' => 'Active',
		'blocked' => 'Blocked'
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

	protected $modelType = CartGlobal::SCHEME_VOUCHER;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use OwnerTrait;
	use VisualTrait;

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
			[ [ 'scheme', 'name', 'amount' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Unique
			[ 'code', 'unique' ],
			// Text Limit
			[ [ 'parentType', 'type', 'code' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'scheme', 'status', 'taxType', 'usageLimit', 'usageCount' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'freeShipping', 'gridCacheValid' ], 'boolean' ],
			[ [ 'amount', 'minPurchase', 'maxDiscount' ], 'number', 'min' => 0 ],
			[ [ 'siteId', 'userId', 'bannerId', 'mbannerId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startsAt', 'endsAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ 'endsAt', 'compareDate', 'compareAttribute' => 'startsAt', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End date and time must be greater than or equal to Start date and time.' ]
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
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'scheme' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX_TYPE ),
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'amount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_AMOUNT ),
			'active' =>  Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'taxType' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TAX_TYPE ),
			'freeShipping' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SHIPPING_FREE ),
			'minPurchase' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_MIN_PURCHASE ),
			'maxDiscount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_MAX_DISCOUNT ),
			'startsAt' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STARTS_AT ),
			'endsAt' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ENDS_AT ),
			'usageLimit' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_USAGE_LIMIT ),
			'usageCount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_USAGE_COUNT ),
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

	// Voucher -------------------------------

	/**
	 * Generate and set the code of voucher.
	 *
	 * @return void
	 */
	public function generateCode() {

		$this->code = Yii::$app->security->generateRandomString( 8 );
	}

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	public function isBlocked() {

		return $this->status == self::STATUS_BLOCKED;
	}

	public function getSchemeStr() {

		return self::$schemeMap[ $this->scheme ];
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	public static function getByCode( $code ) {

		return static::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

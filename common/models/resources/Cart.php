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

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\ModelResourceTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Cart stores the items selected by user for checkout. It will be converted to order
 * after successful checkout.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property string $title
 * @property string $token
 * @property short $status
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
class Cart extends Entity implements IAuthor, IModelResource, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_ACTIVE		= 1000;
	const STATUS_CHECKOUT	= 2000;
	const STATUS_PAYMENT	= 3000;
	const STATUS_SUCCESS	= 4000;
	const STATUS_FAILED		= 5000;
	const STATUS_ABANDONED	= 6000;

	// Public -----------------

	public static $statusMap = [
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_CHECKOUT => 'Checkout',
		self::STATUS_PAYMENT => 'Payment',
		self::STATUS_SUCCESS => 'Success',
		self::STATUS_FAILED => 'Failed',
		self::STATUS_ABANDONED => 'Abandoned'
	];

	public static $revStatusMap = [
		'Active' => self::STATUS_ACTIVE,
		'Checkout' => self::STATUS_CHECKOUT,
		'Payment' => self::STATUS_PAYMENT,
		'Success' => self::STATUS_SUCCESS,
		'Failed' => self::STATUS_FAILED,
		'Abandoned' => self::STATUS_ABANDONED
	];

	public static $urlRevStatusMap = [
		'active' => self::STATUS_ACTIVE,
		'checkout' => self::STATUS_CHECKOUT,
		'payment' => self::STATUS_PAYMENT,
		'success' => self::STATUS_SUCCESS,
		'failed' => self::STATUS_FAILED,
		'abandoned' => self::STATUS_ABANDONED
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CartGlobal::TYPE_CART;

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
			[ 'token', 'required', 'on' => 'guest' ],
			[ 'createdBy', 'required', 'on' => 'user' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type', 'token' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ 'status', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'gridCacheValid', 'boolean' ],
			[ [ 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

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
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'token' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TOKEN ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Cart ----------------------------------

	/**
	 * Returns the items available in cart.
	 *
	 * @return CartItem[]
	 */
	public function getItems() {

		return $this->hasMany( CartItem::class, [ 'cartId' => 'id' ] );
	}

	/**
	 * Returns the active items available in cart.
	 *
	 * @return CartItem[]
	 */
	public function getActiveItems() {

		return $this->hasMany( CartItem::class, [ 'cartId' => 'id' ] )->where( 'keep=1' );
	}

	/**
	 * Generate and set the title of cart.
	 *
	 * @return void
	 */
	public function generateTitle() {

		$this->title = Yii::$app->security->generateRandomString( 16 );
	}

	/**
	 * Returns the total value of cart.
	 *
	 * @param integer $precision
	 * @return float
	 */
	public function getCartTotal( $precision = 2 ) {

		$items	= $this->activeItems;

		$total	= 0;

		foreach( $items as $item ) {

			if( $item->keep ) {

				$total	+= $item->getTotalPrice();
			}
		}

		return round( $total, $precision );
	}

	/**
	 * Returns the total items in cart.
	 *
	 * It accepts $type having column name among primary, purchase, quantity, weight and volume.
	 *
	 * @param string $type
	 * @return float
	 */
	public function getActiveCount( $type = 'purchase' ) {

		if( !in_array( $type, [ 'primary', 'purchase', 'quantity', 'weight', 'volume' ] ) ) {

			return 0;
		}

		$cartItems	= $this->activeItems;
		$count		= 0;

		foreach ( $cartItems as $cartItem ) {

			if( $cartItem->keep ) {

				$count += $cartItem->$type;
			}
		}

		return $count;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_CART );
	}

	// CMG parent classes --------------------

	// Cart ----------------------------------

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

	/**
	 * Find and return the cart associated with given token.
	 *
	 * @param string $token
	 * @return Cart
	 */
	public static function findByToken( $token ) {

		return self::find()->where( 'token=:token', [ 'token' => $token ] )->one();
	}

	/**
	 * Use only if title is unique for cart. Token should be used in most of the cases.
	 *
	 * @param string $title
	 * @return Cart
	 */
	public static function findByTitle( $title ) {

		return self::find()->where( 'title=:title', [ ':title' => $title ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

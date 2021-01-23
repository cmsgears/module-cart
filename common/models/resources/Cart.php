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
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\entities\User;
use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Cart stores the items selected by user for checkout. It will be converted to order
 * after successful checkout.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property string $title
 * @property string $token
 * @property integer $status
 * @property boolean $guest
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $mobile
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
class Cart extends \cmsgears\core\common\models\base\ModelResource implements IAuthor,
	IContent, IData, IGridCache, IMultiSite, IOwner {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_ACTIVE		= 1000;
	const STATUS_ABANDONED	= 2000;
	const STATUS_SUCCESS	= 4000;

	// Public -----------------

	public static $statusMap = [
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_ABANDONED => 'Abandoned',
		self::STATUS_SUCCESS => 'Success'
	];

	public static $revStatusMap = [
		'Active' => self::STATUS_ACTIVE,
		'Abandoned' => self::STATUS_ABANDONED,
		'Success' => self::STATUS_SUCCESS
	];

	public static $urlRevStatusMap = [
		'active' => self::STATUS_ACTIVE,
		'abandoned' => self::STATUS_ABANDONED,
		'success' => self::STATUS_SUCCESS
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CartGlobal::TYPE_CART;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use OwnerTrait;

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
			[ [ 'id', 'content' ], 'safe' ],
			// Unique
			[ 'token', 'unique' ],
			// Text Limit
			[ [ 'parentType', 'type', 'token', 'mobile' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'firstName', 'lastName' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'email' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ 'status', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'guest', 'gridCacheValid' ], 'boolean' ],
			[ [ 'siteId', 'userId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'token' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TOKEN ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'guest' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GUEST ),
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE ),
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

	// Cart ----------------------------------

	/**
	 * Returns the corresponding user.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

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

		$this->title = Yii::$app->security->generateRandomString();
	}

	public function generateToken() {

		$this->token = Yii::$app->security->generateRandomString();
	}

	/**
	 * Returns the total value of cart.
	 *
	 * @param integer $precision
	 * @return float
	 */
	public function getCartTotal( $precision = 2 ) {

		$items = $this->activeItems;

		$total = 0;

		foreach( $items as $item ) {

			if( $item->keep ) {

				$total += $item->getTotalPrice( $precision );
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

		$items = $this->activeItems;

		$count = 0;

		foreach( $items as $item ) {

			if( $item->keep ) {

				$count += $item->$type;
			}
		}

		return $count;
	}

	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	public function isAbandoned() {

		return $this->status == self::STATUS_ABANDONED;
	}

	public function isSuccess() {

		return $this->status == self::STATUS_SUCCESS;
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryByUserId( $userId ) {

		return static::find()->where( 'userId=:uid', [ ':uid' => $userId ] );
	}

	// Read - Find ------------

	public static function findActiveByUserId( $userId ) {

		return static::queryByUserId( $userId )->andWhere( 'status=:status', [ ':status' => self::STATUS_ACTIVE ] )->one();
	}

	public static function findActiveByParentUserId( $parentId, $parentType, $userId ) {

		return static::queryByParent( $parentId, $parentType )->andWhere( 'userId=":uid AND status=:status', [ ':uid' => $userId, ':status' => self::STATUS_ACTIVE ] )->one();
	}

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

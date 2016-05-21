<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\base\CmgEntity;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * CartItem Entity - The primary class.
 *
 * @property integer $id
 * @property integer $quantityUnitId
 * @property integer $weightUnitId
 * @property integer $metricUnitId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property integer $name
 * @property integer $sku
 * @property integer $price
 * @property integer $quantity
 * @property integer $weight
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class CartItem extends CmgEntity {

	use CreateModifyTrait;

	public $addToCart;

	// Instance methods --------------------------------------------------

	public function getTotalPrice() {

		$price	= $this->quantity * $this->price;

		return round( $price, 2 );
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
        	[ [ 'price', 'quantity', 'name' ], 'required' ],
			[ [ 'id', 'quantityUnitId', 'weightUnitId', 'metricUnitId', 'parentId', 'parentType', 'sku', 'weight', 'length', 'width', 'height' ], 'safe' ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'price', 'quantity', 'weight', 'length', 'width', 'height' ], 'number', 'min' => 0 ],
            [ 'addToCart', 'number', 'min' => 0, 'max' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ 'cartId', 'validateCartCreate', 'on' => 'create' ],
			[ 'cartId', 'validateCartUpdate', 'on' => 'update' ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'createdBy' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'quantityUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_QUANTITY ),
			'weightUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_WEIGHT ),
			'metricUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_METRIC ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'sku' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_SKU ),
			'price' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_PRICE ),
			'quantity' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
			'weight' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_WEIGHT ),
			'length' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_LENGTH ),
			'width' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_WIDTH ),
			'height' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_HEIGHT )
		];
	}

	// CartItem --------------

	/**
	 * Validates to ensure that only one item exist for a cart for given parent id and type.
	 */
    public function validateCartCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByParentAndCartId( $this->parentId, $this->parentType, $this->cartId ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one item exist for a cart for given parent id and type.
	 */
    public function validateCartUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingItem = self::findByParentAndCartId( $this->parentId, $this->parentType, $this->cartId );

			if( isset( $existingItem ) && $existingItem->id != $this->id &&
				$existingItem->parentId == $this->parentType && strcmp( $existingItem->parentType, $this->parentType ) == 0 &&
				$existingItem->cartId == $this->cartId ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_CART_ITEM;
	}

	// CartItem -------------------------

	public static function findByCartId( $cartId ) {

		return self::find()->where( 'cartId=:cid', [ ':cid' => $cartId ] )->all();
	}

	public static function findByParentAndCartId( $parentId, $parentType, $cartId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND cartId=:cid',
				[ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $cartId ] )->one();
	}

	public static function isExistByParentAndCartId( $parentId, $parentType, $cartId ) {

		$cartItem = self::findByParentAndCartId( $parentId, $parentType, $cartId );

		return isset( $cartItem );
	}

	public static function deleteByCartId( $cartId ) {

		self::deleteAll( 'cartId=:id', [ ':id' => $cartId ] );
	}
}

?>
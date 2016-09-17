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
 * CartItem Entity - The primary class.
 *
 * @property integer $id
 * @property integer $cartId
 * @property integer $quantityUnitId
 * @property integer $weightUnitId
 * @property integer $metricUnitId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property integer $name
 * @property integer $sku
 * @property integer $price
 * @property integer $quantity
 * @property integer $weight
 * @property integer $length
 * @property integer $width
 * @property integer $height
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

    public $addToCart;

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
            [ [ 'cartId', 'price', 'quantity', 'name' ], 'required' ],
            [ [ 'id', 'content', 'data' ], 'safe' ],
            [ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'name', 'sku' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'price', 'quantity', 'weight', 'length', 'width', 'height' ], 'number', 'min' => 0 ],
            [ 'addToCart', 'boolean' ],
            [ 'cartId', 'validateCartCreate', 'on' => 'create' ],
            [ 'cartId', 'validateCartUpdate', 'on' => 'update' ],
            [ [ 'cartId', 'quantityUnitId', 'weightUnitId', 'metricUnitId', 'parentId' ], 'number', 'integerOnly', 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'cartId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CART ),
            'quantityUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_QUANTITY ),
            'weightUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_WEIGHT ),
            'metricUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_METRIC ),
            'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
            'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'sku' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SKU ),
            'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE ),
            'quantity' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
            'weight' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_WEIGHT ),
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

    /**
     * Validates to ensure that only one item exist for a cart for given parent id and type.
     */
    public function validateCartCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByParentCartId( $this->parentId, $this->parentType, $this->cartId ) ) {

                $this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    /**
     * Validates to ensure that only one item exist for a cart for given parent id and type.
     */
    public function validateCartUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingItem = self::findByParentCartId( $this->parentId, $this->parentType, $this->cartId );

            if( isset( $existingItem ) && $existingItem->id != $this->id ) {

                $this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    // CartItem ------------------------------

    public function getCart() {

        return $this->hasOne( Cart::className(), [ 'id' => 'cartId' ] );
    }

    public function getQuantityUnit() {

        return $this->hasOne( Option::className(), [ 'id' => 'quantityUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as qUnit' );
    }

    public function getWeightUnit() {

        return $this->hasOne( Option::className(), [ 'id' => 'weightUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as wUnit' );
    }

    public function getMetricUnit() {

        return $this->hasOne( Option::className(), [ 'id' => 'metricUnitId' ] )->from( CoreTables::TABLE_OPTION . ' as mUnit' );
    }

    public function getTotalPrice() {

        $price	= $this->quantity * $this->price;

        return round( $price, 2 );
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

    public static function queryWithAll( $config = [] ) {

        $relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'cart', 'quantityUnit', 'weightUnit', 'metricUnit', 'creator' ];
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

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND cartId=:cid',
                [ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $cartId ] )->one();
    }

    public static function isExistByParentCartId( $parentId, $parentType, $cartId ) {

        $cartItem = self::findByParentCartId( $parentId, $parentType, $cartId );

        return isset( $cartItem );
    }

    // Create -----------------

    // Update -----------------

    // Delete -----------------

    public static function deleteByCartId( $cartId ) {

        self::deleteAll( 'cartId=:id', [ ':id' => $cartId ] );
    }
}

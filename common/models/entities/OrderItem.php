<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgEntity;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * OrderItem Entity - The primary class.
 *
 * @property integer $id
 * @property integer $orderId
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
 * @property integer $discount
 * @property integer $quantity
 * @property integer $weight
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class OrderItem extends CmgEntity {

	use CreateModifyTrait;

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
        	[ [ 'orderId', 'price', 'quantity', 'name' ], 'required' ],
			[ [ 'id', 'quantityUnitId', 'weightUnitId', 'metricUnitId', 'parentId', 'parentType', 'sku', 'discount', 'weight', 'length', 'width', 'height' ], 'safe' ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'price', 'quantity', 'weight', 'length', 'width', 'height' ], 'number', 'min' => 0 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'orderId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_ORDER ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'createdBy' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'quantityUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_QUANTITY ),
			'weightUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_WEIGHT ),
			'metricUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_METRIC ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'sku' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_SKU ),
			'price' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_PRICE ),
			'discount' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'quantity' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
			'weight' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_WEIGHT ),
			'length' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_LENGTH ),
			'width' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_WIDTH ),
			'height' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_HEIGHT )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_ORDER_ITEM;
	}

	// CartItem -------------------------

	public static function findByOrderId( $oderId ) {
		
		return self::find()->where( 'orderId=:id', [ ':id' => $oderId ] )->all();
	}
}

?>
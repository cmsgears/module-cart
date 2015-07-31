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
 * CartItem Entity - The primary class.
 *
 * @property integer $id
 * @property integer $weightUnitId
 * @property integer $metricUnitId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType 
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

	// Instance methods --------------------------------------------------

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
        	[ [ 'price', 'quantity' ], 'required' ],
			[ [ 'id', 'weightUnitId', 'metricUnitId', 'parentId', 'parentType', 'weight', 'length', 'width', 'height' ], 'safe' ],
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
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'createdBy' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'weightUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_WEIGHT ),
			'metricUnitId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_UNIT_METRIC ),
			'price' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_PRICE ),
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

		return CartTables::TABLE_CART_ITEM;
	}

	// CartItem -------------------------

}

?>
<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\CmgEntity;

/**
 * OrderTransaction Entity - The primary class.
 *
 * @property integer $id
 * @property integer $orderId
 * @property string $code
 * @property integer $type
 * @property string $mode
 * @property datetime $createdAt
 */
class OrderTransaction extends CmgEntity {

	// Instance methods --------------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
        	[ [ 'orderId', 'type', 'mode' ] => 'required' ],
			[ [ 'id', 'status', 'code' ], 'safe' ],
			[ [ 'createdAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'status' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TXN_CODE ),
			'code' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TXN_TYPE ),
			'mode' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TXN_MODE )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_ORDER_TRANSACTION;
	}

	// OrderTransaction ------------------

}

?>
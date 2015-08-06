<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\CmgEntity;

use cmsgears\core\common\models\traits\AddressTrait;
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * OrderHistory Entity - The primary class.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $status
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class OrderHistory extends CmgEntity {

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
			[ [ 'id', 'status' ], 'safe' ],
            [ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_ORDER_HISTORY;
	}

	// OrderHistory ----------------------

}

?>
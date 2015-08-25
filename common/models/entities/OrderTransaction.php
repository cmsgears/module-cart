<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\CmgEntity;

/**
 * OrderTransaction Entity - The primary class.
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $createdBy
 * @property string $code
 * @property string $description
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

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
        	[ [ 'orderId', 'code', 'type' ], 'required' ],
			[ [ 'id', 'description', 'mode' ], 'safe' ],
			[ [ 'createdAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'code' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TXN_CODE ),
			'type' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TXN_TYPE ),
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
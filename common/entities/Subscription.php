<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\entities\CmgEntity;
use cmsgears\core\common\models\entities\User;

class Subscription extends CmgEntity {

	const STATUS_TRIAL			=  0;
	const STATUS_ACTIVE			=  5;
	const STATUS_SUSPENDED		= 10;
	const STATUS_CANCELLED		= 15;

	public static $statusMap = [
		self::STATUS_TRIAL => "trial",
		self::STATUS_ACTIVE => "active",
		self::STATUS_SUSPENDED => "suspended",
		self::STATUS_CANCELLED => "cancelled"
	];

	// Instance Methods --------------------------------------------

	public function getUser() {

    	return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	public function getProduct() {

    	return $this->hasOne( Product::className(), [ 'id' => 'productId' ] );
	}

	public function getPlan() {

    	return $this->hasOne( ProductPlan::className(), [ 'id' => 'planId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'userId', 'productId', 'planId', 'period', 'trial', 'price' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'period', 'trial', 'interval' ], 'number', 'integerOnly' => true ],
            [ 'price', 'number', 'min' => 0 ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'description' => 'Description',
			'mode' => 'Operation Mode',
			'chargeType' => 'Charge Type',
			'chargeAmount' => 'Charge Amount'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_PRODUCT_PLAN;
	}

	// Subscription ---------------------

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
	
	public static function findByUserId( $id ) {

		return self::find()->where( 'userId=:id', [ ':id' => $id ] )->all();
	}
}

?>
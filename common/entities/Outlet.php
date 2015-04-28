<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\entities\CmgEntity;

class Merchant extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getMerchant() {

    	return $this->hasOne( Merchant::className(), [ 'id' => 'merchantId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'merchantId', 'name' ], 'required' ],
            [ [ 'locationId', 'description' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'merchantId' => 'Merchant',
			'locationId' => 'Location',
			'name' => 'Name',
			'description' => 'Description'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_OUTLET;
	}

	// Merchant --------------------------

}

?>
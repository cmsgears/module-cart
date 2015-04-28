<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\entities\CmgEntity;
use cmsgears\core\common\models\entities\User;

class Merchant extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getUser() {

    	return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	public function getMall() {

    	return $this->hasOne( Mall::className(), [ 'id' => 'mallId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'userId', 'mallId' ], 'required' ],
            [ 'id', 'safe' ],
            [ [ 'userId', 'mallId'], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

	public function attributeLabels() {

		return [
			'userId' => 'User',
			'mallId' => 'Mall'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_MERCHANT;
	}

	// Merchant --------------------------

}

?>
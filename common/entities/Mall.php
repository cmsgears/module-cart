<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\entities\NamedCmgEntity;

class Mall extends NamedCmgEntity {

	const MODE_CART				=  0;
	const MODE_MALL				= 10;

	const CHARGE_SALE_FLAT		=  0;
	const CHARGE_SALE_PERCENT	= 10;
	const CHARGE_MONTHLY		= 20;

	// Instance Methods --------------------------------------------

	public function getStores() {

    	return $this->hasMany( Page::className(), [ 'id' => 'pageId' ] )
					->viaTable( CMSTables::TABLE_PAGE, [ 'menuId' => 'id' ] );
	}

	public function getOutlets() {

    	// TODO - Write has many to return all the outlets
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name', 'mode' ], 'required' ],
            [ [ 'id', 'description', 'chargeType', 'chargeAmount' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'mode', 'chargeType'], 'number', 'integerOnly' => true ],
            [ 'chargeAmount', 'number', 'min' => 0 ]
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

		return CartTables::TABLE_MALL;
	}

	// Mall ------------------------------

}

?>
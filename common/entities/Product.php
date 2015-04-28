<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\NamedCmgEntity;
use cmsgears\core\common\models\entities\MetaTrait;
use cmsgears\core\common\models\entities\CategoryTrait;

class Product extends NamedCmgEntity {

	// Pre-Defined Types
	const TYPE_REGULAR		= 0;
	const TYPE_SUBSCRIPTION	= 5;

	public static $typeMap = [
		self::TYPE_REGULAR => "regular",
		self::TYPE_SUBSCRIPTION => "subscription"
	];

	// Pre-Defined Status
	const STATUS_NEW		= 0;
	const STATUS_PUBLISHED	= 5;

	public static $statusMap = [
		self::STATUS_NEW => "new",
		self::STATUS_PUBLISHED => "published"
	];

	// Pre-Defined Visibility
	const VISIBILITY_PRIVATE	= 0;
	const VISIBILITY_PUBLIC		= 5;

	public static $visibilityMap = [
		self::VISIBILITY_PRIVATE => "private",
		self::VISIBILITY_PUBLIC => "public"
	];

	use MetaTrait;

	public $parentType	= CartGlobal::META_TYPE_PRODUCT;

	use CategoryTrait;

	public $parentType	= CartGlobal::CATEGORY_TYPE_PRODUCT;

	// Instance Methods --------------------------------------------

	public function getVariations() {

    	return $this->hasMany( ProductVariation::className(), [ 'productId' => 'id' ] );
	}

	public function getPlans() {

    	return $this->hasMany( ProductPlan::className(), [ 'productId' => 'id' ] );
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

		return CartTables::TABLE_PRODUCT;
	}

	// Product ---------------------------

}

?>
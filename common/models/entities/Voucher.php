<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\NamedCmgEntity;

class Voucher extends NamedCmgEntity {

	const TYPE_CART				=  0;
	const TYPE_CART_PERCENT		=  5;
	const TYPE_PRODUCT			= 10;
	const TYPE_PRODUCT_PERCENT	= 15;

	const TAX_BEFORE_DISCOUNT	= 0;
	const TAX_AFTER_DISCOUNT	= 5;

	public static $typesMap = array(
	    self::TYPE_CART  => "Cart $",
	    self::TYPE_CART_PERCENT => "Cart %",
	    self::TYPE_PRODUCT => "Product $",
	    self::TYPE_PRODUCT_PERCENT => "Product %"
	   	);

	public static $typesMapRev = array(
	    "Cart $" => self::TYPE_CART,
	    "Cart %" => self::TYPE_CART_PERCENT,
	    "Product $" => self::TYPE_PRODUCT,
	    "Product %" => self::TYPE_PRODUCT_PERCENT
	   	);

	public static $taxTypeMap = array(
	    self::TAX_BEFORE_DISCOUNT  => "Before discount",
	    self::TAX_AFTER_DISCOUNT => "After discount"
	   	);

	public static $taxTypeMapRev = array(
	    "Before discount" => self::TAX_BEFORE_DISCOUNT,
	    "After discount" => self::TAX_AFTER_DISCOUNT
	   	);

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name', 'type', 'amount', 'taxType', 'freeShipping', 'minPurchase', 'maxDiscount' ], 'required' ],
            [ [ 'id', 'description', 'usageLimit', 'usageCount' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'taxType', 'freeShipping', 'usageLimit', 'usageCount' ], 'number', 'integerOnly' => true ],
            [ [ 'amount', 'minPurchase', 'maxDiscount' ], 'number', 'min' => 0 ],
            [ [ 'startTime', 'endTime', 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'amount' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_AMOUNT ),
			'taxType' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TAX_TYPE ),
			'shippingType' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_SHIPPING_TYPE ),
			'minPurchase' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_MIN_PURCHASE ),
			'maxDiscount' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_MAX_DISCOUNT ),
			'usageLimit' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_USAGE_LIMIT ),
			'usageCount' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_USAGE_COUNT ),
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_VOUCHER;
	}

	// Voucher --------------------------

}

?>
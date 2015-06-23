<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\entities\NamedCmgEntity;

class Coupon extends NamedCmgEntity {

	const TYPE_CART				=  0;
	const TYPE_CART_PERCENT		=  5;
	const TYPE_PRODUCT			= 10;
	const TYPE_PRODUCT_PERCENT	= 15;

	const TAX_BEFORE_DISCOUNT	= 0;
	const TAX_AFTER_DISCOUNT	= 5;

	const SHIPPING_PAID			= 0;
	const SHIPPING_FREE			= 5;

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

	public static $shippingTypeMap = array(
	    EmblmCoupon::SHIPPING_PAID  => "Paid",
	    EmblmCoupon::SHIPPING_FREE => "Free"
	   	);


	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name', 'type', 'amount', 'taxType', 'shippingType', 'minPurchase', 'maxDiscount', 'usageLimit' ], 'required' ],
            [ [ 'id', 'description', 'usageCount' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'taxType', 'shippingType', 'usageLimit', 'usageCount' ], 'number', 'integerOnly' => true ],
            [ [ 'amount', 'minPurchase', 'maxDiscount' ], 'number', 'min' => 0 ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'description' => 'Description',
			'amount' => 'Amount',
			'taxType' => 'Tax Type',
			'shippingType' => 'Shipping Type',
			'minPurchase' => 'Minimum Purchase', 
			'maxDiscount' => 'Maximum Discount',
			'usageLimit' => 'Usage Limit',
			'usageCount' => 'Usage Count'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_COUPON;
	}

	// Coupon ---------------------------
		
}

?>
<?php
namespace cmsgears\cart\common\components;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

class MessageSource extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		CartGlobal::FIELD_SKU => 'Sku',
		CartGlobal::FIELD_CART => 'Cart',

		// Vouchers
		CartGlobal::FIELD_TAX_TYPE => 'Tax Type',
		CartGlobal::FIELD_SHIPPING_FREE => 'Free Shipping',
		CartGlobal::FIELD_MIN_PURCHASE => 'Minimum Purchase',
		CartGlobal::FIELD_MAX_DISCOUNT => 'Maximum Discount',
		CartGlobal::FIELD_USAGE_LIMIT => 'Usage Limit',
		CartGlobal::FIELD_USAGE_COUNT => 'Usage Count',

		// Units
		CartGlobal::FIELD_UNIT_PRIMARY => 'Primary Unit',
		CartGlobal::FIELD_UNIT_PURCHASING => 'Purchasing Unit',
		CartGlobal::FIELD_UNIT_QUANTITY => 'Quantity Unit',
		CartGlobal::FIELD_UNIT_WEIGHT => 'Weight Unit',
		CartGlobal::FIELD_UNIT_SIZE => 'Size Unit',
		CartGlobal::FIELD_UNIT_VOLUME => 'Volume Unit',
		CartGlobal::FIELD_UNIT_LENGTH => 'Length Unit',
		CartGlobal::FIELD_QTY_PRIMARY => 'Primary Quantity',
		CartGlobal::FIELD_QTY_PURCHASE => 'Purchase Quantity',
		CartGlobal::FIELD_QUANTITY => 'Quantity',
		CartGlobal::FIELD_QUANTITY_TRACK => 'Track Quantity',
		CartGlobal::FIELD_QUANTITY_STOCK => 'Stock Quantity',
		CartGlobal::FIELD_QUANTITY_SOLD => 'Quantity Sold',
		CartGlobal::FIELD_QUANTITY_WARN => 'Warn Quantity',
		CartGlobal::FIELD_WEIGHT => 'Weight',
		CartGlobal::FIELD_VOLUME => 'Volume',
		CartGlobal::FIELD_SIZE, 'Size',
		CartGlobal::FIELD_LENGTH => 'Length',
		CartGlobal::FIELD_WIDTH => 'Width',
		CartGlobal::FIELD_HEIGHT => 'Height',
		CartGlobal::FIELD_RADIUS => 'Radius',

		// Totals
		CartGlobal::FIELD_PRICE => 'Price',
		CartGlobal::FIELD_PRICE_UNIT => 'Unit Price',
		CartGlobal::FIELD_TOTAL_SUB	=> 'Sub Total',
		CartGlobal::FIELD_TAX => 'Tax',
		CartGlobal::FIELD_SHIPPING => 'Shipping',
		CartGlobal::FIELD_TOTAL => 'Total',
		CartGlobal::FIELD_DISCOUNT => 'Discount',
		CartGlobal::FIELD_DISCOUNT_UNIT => 'Unit Discount',
		CartGlobal::FIELD_DISCOUNT_TYPE => 'Discount Type',
		CartGlobal::FIELD_TOTAL_GRAND => 'Grand Total',

		// Orders
		CartGlobal::FIELD_ORDER => 'Order',
		CartGlobal::FIELD_PARENT_ORDER => 'Parent Order',
		CartGlobal::FIELD_SHIP_TO_BILLING => 'Same as Billing Address',
		CartGlobal::FIELD_ESTIMATED_DELIVERY => 'Estimated Delivery Date',
		CartGlobal::FIELD_DELIVERY_DATE => 'Delivery Date'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}

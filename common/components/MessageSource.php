<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
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
		CartGlobal::FIELD_SHIPPING_TYPE => 'Shipping Type',
		CartGlobal::FIELD_MIN_PURCHASE => 'Minimum Purchase',
		CartGlobal::FIELD_MAX_DISCOUNT => 'Maximum Discount',
		CartGlobal::FIELD_USAGE_LIMIT => 'Usage Limit',
		CartGlobal::FIELD_USAGE_COUNT => 'Usage Count',

		// Units
		CartGlobal::FIELD_UNIT_PRIMARY => 'Primary Unit',
		CartGlobal::FIELD_UNIT_QUANTITY => 'Quantity Unit',
		CartGlobal::FIELD_UNIT_WEIGHT => 'Weight Unit',
		CartGlobal::FIELD_UNIT_LENGTH => 'Length Unit',
		CartGlobal::FIELD_UNIT_VOLUME => 'Volume Unit',
		CartGlobal::FIELD_PRIMARY => 'Primary',
		CartGlobal::FIELD_QUANTITY => 'Quantity',
		CartGlobal::FIELD_WEIGHT => 'Weight',
		CartGlobal::FIELD_LENGTH => 'Length',
		CartGlobal::FIELD_WIDTH => 'Width',
		CartGlobal::FIELD_HEIGHT => 'Height',
		CartGlobal::FIELD_RADIUS => 'Radius',
		CartGlobal::FIELD_VOLUME => 'Volume',

		// Totals
		CartGlobal::FIELD_AMOUNT => 'Amount',
		CartGlobal::FIELD_PRICE => 'Price',
		CartGlobal::FIELD_TOTAL_SUB	=> 'Sub Total',
		CartGlobal::FIELD_TAX => 'Tax',
		CartGlobal::FIELD_SHIPPING => 'Shipping',
		CartGlobal::FIELD_TOTAL => 'Total',
		CartGlobal::FIELD_DISCOUNT => 'Discount',
		CartGlobal::FIELD_TOTAL_GRAND => 'Grand Total',

		// Orders
		CartGlobal::FIELD_PARENT_ORDER => 'Parent Order',
		CartGlobal::FIELD_ORDER => 'Order',
		CartGlobal::FIELD_DELIVERY_DATE => 'Delivery Date',

		// Transactions
		CartGlobal::FIELD_TXN_CODE => 'Code',
		CartGlobal::FIELD_TXN_TYPE => 'Type',
		CartGlobal::FIELD_TXN_MODE => 'Mode'
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

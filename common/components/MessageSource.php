<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	private $messageDb = [
		// Generic Fields
		CartGlobal::FIELD_AMOUNT => 'Amount',
		CartGlobal::FIELD_TAX_TYPE => 'Tax Type',
		CartGlobal::FIELD_SHIPPING_TYPE => 'Shipping Type',
		CartGlobal::FIELD_MIN_PURCHASE => 'Minimum Purchase',
		CartGlobal::FIELD_MAX_DISCOUNT => 'Maximum Discount',
		CartGlobal::FIELD_USAGE_LIMIT => 'Usage Limit',
		CartGlobal::FIELD_USAGE_COUNT => 'Usage Count',
		CartGlobal::FIELD_UNIT_QUANTITY => 'Quantity Unit',
		CartGlobal::FIELD_UNIT_WEIGHT => 'Weight Unit',
		CartGlobal::FIELD_UNIT_METRIC => 'Metrict Unit',
		CartGlobal::FIELD_SKU => 'Sku',
		CartGlobal::FIELD_PRICE => 'Price',
		CartGlobal::FIELD_QUANTITY => 'Quantity',
		CartGlobal::FIELD_WEIGHT => 'Weight',
		CartGlobal::FIELD_LENGTH => 'Length',
		CartGlobal::FIELD_WIDTH => 'Width',
		CartGlobal::FIELD_HEIGHT => 'Height',
		CartGlobal::FIELD_TOTAL_SUB	=> 'Sub Total',
		CartGlobal::FIELD_TAX => 'Tax',
		CartGlobal::FIELD_SHIPPING => 'Shipping',
		CartGlobal::FIELD_TOTAL => 'Total',
		CartGlobal::FIELD_DISCOUNT => 'Discount',
		CartGlobal::FIELD_TOTAL_GRAND => 'Grand Total',
		CartGlobal::FIELD_PARENT_ORDER => 'Parent Order',
		CartGlobal::FIELD_ORDER => 'Order',
		CartGlobal::FIELD_DELIVERY_DATE => 'Delivery Date',
		CartGlobal::FIELD_TXN_CODE => 'Code',
		CartGlobal::FIELD_TXN_TYPE => 'Type',
		CartGlobal::FIELD_TXN_MODE => 'Mode'
	];

	/**
	 * Initialise the Cms Message DB Component.
	 */
    public function init() {

        parent::init();
    }

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}

?>
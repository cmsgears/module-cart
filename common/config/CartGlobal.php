<?php
namespace cmsgears\cart\common\config;

class CartGlobal {
	
	// Transactions ----------------------------------------------------
	
	const TXN_MODE_ORDER_PAYMENT		= 'Order Payment';
	
	// Model Traits - Metas, Attachments, Addresses --------------------
	
	const TYPE_ORDER					= 'order';

	// Config ----------------------------------------------------------

	// Roles -----------------------------------------------------------

	// Permissions -----------------------------------------------------

	// Admin Permissions
	const PERM_CART						= 'cart';

	// Website Permissions

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_AMOUNT					= 'amountField';
	const FIELD_TAX_TYPE				= 'taxTypeField';
	const FIELD_SHIPPING_TYPE			= 'shippingTypeField';
	const FIELD_MIN_PURCHASE			= 'minPurchaseField';
	const FIELD_MAX_DISCOUNT			= 'maxDiscountField';
	const FIELD_USAGE_LIMIT				= 'usageLimitField';
	const FIELD_USAGE_COUNT				= 'usageCountField';

	const FIELD_UNIT_QUANTITY			= 'quantityUnitField';
	const FIELD_UNIT_WEIGHT				= 'weightUnitField';
	const FIELD_UNIT_METRIC				= 'metricUnitField';
	const FIELD_SKU						= 'skuField';
	const FIELD_PRICE					= 'priceField';
	const FIELD_QUANTITY				= 'quantityField';
	const FIELD_WEIGHT					= 'weightField';
	const FIELD_LENGTH					= 'lengthField';
	const FIELD_WIDTH					= 'widthField';
	const FIELD_HEIGHT					= 'heightField';

	const FIELD_TOTAL_SUB				= 'subTotalField';
	const FIELD_TAX						= 'taxField';
	const FIELD_SHIPPING				= 'shippingField';
	const FIELD_TOTAL					= 'totalField';
	const FIELD_DISCOUNT				= 'discountField';
	const FIELD_TOTAL_GRAND				= 'grandTotalField';
	
	const FIELD_ORDER					= 'orderField';
	const FIELD_DELIVERY_DATE			= 'deliveryDateField';

	const FIELD_TXN_CODE				= 'txnCodeField';
	const FIELD_TXN_TYPE				= 'txnTypeField';
	const FIELD_TXN_MODE				= 'txnModeField';
}

?>
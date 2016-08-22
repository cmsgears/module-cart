<?php
namespace cmsgears\cart\common\config;

class CartGlobal {

    // Config ----------------------------------------------------------

    const CONFIG_CART	       			= 'cart';

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

	// Template Views --------------------------------------------------

	// Simple checkout
	const TEMPLATE_VIEW_CHECKOUT		= 'checkout';

	// User/Guest checkout
    const TEMPLATE_VIEW_CHECKOUT_GUEST	= 'checkout-guest';
	const TEMPLATE_VIEW_CHECKOUT_USER	= 'checkout-user';

    const TEMPLATE_VIEW_ORDER_CONFIRM	= 'confirm';

	const TEMPLATE_VIEW_PAYMENT			= 'payment';
	const TEMPLATE_VIEW_PAYMENT_SUCCESS	= 'payment-success';
	const TEMPLATE_VIEW_PAYMENT_FAILURE	= 'payment-failure';

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_SKU						= 'skuField';
	const FIELD_CART					= 'cartField';

	// Vouchers
	const FIELD_TAX_TYPE				= 'taxTypeField';
	const FIELD_SHIPPING_TYPE			= 'shippingTypeField';
	const FIELD_MIN_PURCHASE			= 'minPurchaseField';
	const FIELD_MAX_DISCOUNT			= 'maxDiscountField';
	const FIELD_USAGE_LIMIT				= 'usageLimitField';
	const FIELD_USAGE_COUNT				= 'usageCountField';

	// Units
	const FIELD_UNIT_QUANTITY			= 'quantityUnitField';
	const FIELD_UNIT_WEIGHT				= 'weightUnitField';
	const FIELD_UNIT_METRIC				= 'metricUnitField';
	const FIELD_QUANTITY				= 'quantityField';
	const FIELD_WEIGHT					= 'weightField';
	const FIELD_LENGTH					= 'lengthField';
	const FIELD_WIDTH					= 'widthField';
	const FIELD_HEIGHT					= 'heightField';

	// Totals
	const FIELD_AMOUNT					= 'amountField';
	const FIELD_PRICE					= 'priceField';
	const FIELD_TOTAL_SUB				= 'subTotalField';
	const FIELD_TAX						= 'taxField';
	const FIELD_SHIPPING				= 'shippingField';
	const FIELD_TOTAL					= 'totalField';
	const FIELD_DISCOUNT				= 'discountField';
	const FIELD_TOTAL_GRAND				= 'grandTotalField';

	// Orders
	const FIELD_ORDER					= 'orderField';
	const FIELD_PARENT_ORDER			= 'parentOrderField';
	const FIELD_DELIVERY_DATE			= 'deliveryDateField';

	// Transactions
	const FIELD_TXN_CODE				= 'txnCodeField';
	const FIELD_TXN_TYPE				= 'txnTypeField';
	const FIELD_TXN_MODE				= 'txnModeField';
}

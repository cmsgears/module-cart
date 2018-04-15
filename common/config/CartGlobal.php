<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\config;

/**
 * CmsGlobal defines the global constants and variables available for cms and dependent modules.
 *
 * @since 1.0.0
 */
class CartGlobal {

	// System Sites ---------------------------------------------------

	// System Pages ---------------------------------------------------

	// Grouping by type ------------------------------------------------

	const TYPE_CART			= 'cart';
	const TYPE_CART_ITEM	= 'cart-item';

	const TYPE_ORDER		= 'order';
	const TYPE_ORDER_ITEM	= 'order-item';

	const TYPE_VOUCHER		= 'voucher';

	// Templates -------------------------------------------------------

	// Shop - Selection
	const TEMPLATE_VIEW_SHOP		= 'shop/index';

	// Checkout - Simple
	const TEMPLATE_VIEW_CHECKOUT	= 'checkout/index';

	// Checkout - User or Guest
	const TEMPLATE_VIEW_CHECKOUT_GUEST		= 'checkout/guest';
	const TEMPLATE_VIEW_CHECKOUT_USER		= 'checkout/user';

	// Checkout - Process
	const TEMPLATE_VIEW_CHECKOUT_PROCESS	= 'checkout/process';

	// Checkout - Address
	const TEMPLATE_VIEW_CHECKOUT_ADDRESS	= 'checkout/address';

	// Order confirmation before placing it
	const TEMPLATE_VIEW_ORDER_CONFIRM	= 'order/confirm';

	// Order success/failure - free orders
	const TEMPLATE_VIEW_ORDER_SUCCESS	= 'order/success';
	const TEMPLATE_VIEW_ORDER_FAILED	= 'order/failed';

	// Payment selection
	const TEMPLATE_VIEW_ORDER_PAYMENT	= 'order/payment';

	// Order Printing
	const TEMPLATE_VIEW_ORDER_PRINT		= 'order/print';

	// Order success/failure - paid orders
	const TEMPLATE_VIEW_PAYMENT_SUCCESS	= 'payment/success';
	const TEMPLATE_VIEW_PAYMENT_FAILED	= 'payment/failed';

	// Config ----------------------------------------------------------

	const CONFIG_CART	= 'cart';

	// Roles -----------------------------------------------------------

	// Order Roles
	const ROLE_ORDER_ADMIN		= 'order-admin';

	// Permissions -----------------------------------------------------

	// Order Permissions
	const PERM_ORDER_ADMIN		= 'admin-orders';

	const PERM_ORDER_MANAGE		= 'manage-orders';

	const PERM_ORDER_VIEW		= 'view-orders';
	const PERM_ORDER_ADD		= 'add-order';
	const PERM_ORDER_UPDATE		= 'update-order';
	const PERM_ORDER_DELETE		= 'delete-order';
	const PERM_ORDER_APPROVE	= 'approve-order';
	const PERM_ORDER_PRINT		= 'print-order';
	const PERM_ORDER_IMPORT		= 'import-orders';
	const PERM_ORDER_EXPORT		= 'export-orders';

	// Model Attributes ------------------------------------------------

	// Default Maps ----------------------------------------------------

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_SKU						= 'skuField';
	const FIELD_CART					= 'cartField';

	// Vouchers
	const FIELD_TAX_TYPE				= 'taxTypeField';
	const FIELD_SHIPPING_FREE			= 'freeShippingField';
	const FIELD_MIN_PURCHASE			= 'minPurchaseField';
	const FIELD_MAX_DISCOUNT			= 'maxDiscountField';
	const FIELD_USAGE_LIMIT				= 'usageLimitField';
	const FIELD_USAGE_COUNT				= 'usageCountField';

	// Units
	const FIELD_UNIT_PRIMARY			= 'primaryUnitField';
	const FIELD_UNIT_PURCHASING			= 'purchasingUnitField';
	const FIELD_UNIT_QUANTITY			= 'quantityUnitField';
	const FIELD_UNIT_WEIGHT				= 'weightUnitField';
	const FIELD_UNIT_VOLUME				= 'volumeUnitField';
	const FIELD_UNIT_SIZE				= 'sizeUnitField';
	const FIELD_UNIT_LENGTH				= 'lengthUnitField';
	const FIELD_QTY_PRIMARY				= 'primaryQtyField';
	const FIELD_QTY_PURCHASE			= 'purchaseQtyField';
	const FIELD_QUANTITY				= 'quantityField';
	const FIELD_QUANTITY_TRACK			= 'quantityTrackField';
	const FIELD_QUANTITY_STOCK			= 'quantityStockField';
	const FIELD_QUANTITY_SOLD			= 'quantitySoldField';
	const FIELD_QUANTITY_WARN			= 'quantityWarnField';
	const FIELD_WEIGHT					= 'weightField';
	const FIELD_VOLUME					= 'volumeField';
	const FIELD_SIZE					= 'sizeField';
	const FIELD_LENGTH					= 'lengthField';
	const FIELD_WIDTH					= 'widthField';
	const FIELD_HEIGHT					= 'heightField';
	const FIELD_RADIUS					= 'radiusField';

	// Totals
	const FIELD_PRICE					= 'priceField';
	const FIELD_PRICE_UNIT				= 'priceUnitField';
	const FIELD_TOTAL_SUB				= 'subTotalField';
	const FIELD_TAX						= 'taxField';
	const FIELD_SHIPPING				= 'shippingField';
	const FIELD_TOTAL					= 'totalField';
	const FIELD_DISCOUNT				= 'discountField';
	const FIELD_DISCOUNT_UNIT			= 'discountUnitField';
	const FIELD_DISCOUNT_TYPE			= 'discountTypeField';
	const FIELD_TOTAL_GRAND				= 'grandTotalField';

	// Orders
	const FIELD_ORDER					= 'orderField';
	const FIELD_PARENT_ORDER			= 'parentOrderField';
	const FIELD_SHIP_TO_BILLING			= 'shipToBillingField';
	const FIELD_ESTIMATED_DELIVERY		= 'estimatedDeliveryField';
	const FIELD_DELIVERY_DATE			= 'deliveryDateField';

}

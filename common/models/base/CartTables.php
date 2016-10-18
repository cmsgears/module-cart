<?php
namespace cmsgears\cart\common\models\base;

class CartTables {

	// Entities -------------

	// Cart
	const TABLE_CART				= 'cmg_cart';
	const TABLE_CART_ITEM			= 'cmg_cart_item';

	// Order
	const TABLE_ORDER				= 'cmg_cart_order';
	const TABLE_ORDER_ITEM			= 'cmg_cart_order_item';
	const TABLE_VOUCHER				= 'cmg_cart_voucher';

	// Resources ------------

	// UOM
	const TABLE_UOM					= 'cmg_cart_uom';
	const TABLE_UOM_CONVERSION		= 'cmg_cart_uom_conversion';

	// Order
	const TABLE_ORDER_HISTORY		= 'cmg_cart_order_history';

	// Mappers --------------
}

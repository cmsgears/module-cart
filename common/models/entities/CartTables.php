<?php
namespace cmsgears\cart\common\models\entities;

class CartTables {

	// Variables ---------------------------------------------------

	// Static Variables --------------------

	// Cart
	const TABLE_CART				= "cmg_cart";
	const TABLE_CART_ITEM			= "cmg_cart_item";

	// Order
	const TABLE_ORDER				= "cmg_cart_order";
	const TABLE_ORDER_HISTORY		= "cmg_cart_order_history";
	const TABLE_ORDER_TRANSACTION	= "cmg_cart_order_txn";
	const TABLE_ORDER_ITEM			= "cmg_cart_order_item";
	const TABLE_VOUCHER				= "cmg_cart_voucher";
}

?>
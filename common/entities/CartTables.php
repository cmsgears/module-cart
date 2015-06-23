<?php
namespace cmsgears\cart\common\models\entities;

class CartTables {

	// Variables ---------------------------------------------------

	// Static Variables --------------------

	// Mall and Outlets
	const TABLE_MALL			= "cmg_cart_vmall";
	const TABLE_MERCHANT		= "cmg_cart_merchant";
	const TABLE_OUTLET			= "cmg_cart_outlet";

	// Cart Product
	const TABLE_PRODUCT				= "cmg_cart_product";
	const TABLE_PRODUCT_VARIATION	= "cmg_cart_product_variant";
	const TABLE_PRODUCT_PLAN		= "cmg_cart_product_plan";

	// Discount Coupons
	const TABLE_COUPON				= "cmg_cart_coupon";

	// Subscriptions
	const TABLE_SUBSCRIPTION			= "cmg_cart_sub";
	const TABLE_SUBSCRIPTION_PAYMENT	= "cmg_cart_sub_payment";
}

?>
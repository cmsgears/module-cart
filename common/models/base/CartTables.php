<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\models\base;

// CMG Imports
use cmsgears\core\common\models\base\DbTables;

/**
 * It provide table name constants of db tables available in Cart Module.
 *
 * @since 1.0.0
 */
class CartTables extends DbTables {

	// Entities -------------

	// Cart
	const TABLE_CART			= 'cmg_cart';
	const TABLE_CART_ITEM		= 'cmg_cart_item';

	// Order
	const TABLE_ORDER			= 'cmg_cart_order';
	const TABLE_ORDER_ITEM		= 'cmg_cart_order_item';
	const TABLE_VOUCHER			= 'cmg_cart_voucher';

	// Resources ------------

	// UOM
	const TABLE_UOM				= 'cmg_cart_uom';
	const TABLE_UOM_CONVERSION	= 'cmg_cart_uom_conversion';

	// Mappers --------------

}

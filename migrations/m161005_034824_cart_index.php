<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

/**
 * The cart index migration inserts the recommended indexes for better performance. It
 * also list down other possible index commented out. These indexes can be created using
 * project based migration script.
 *
 * @since 1.0.0
 */
class m161005_034824_cart_index extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// UOM
		$this->createIndex( 'idx_' . $this->prefix . 'uom_name', $this->prefix . 'cart_uom', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'uom_code', $this->prefix . 'cart_uom', 'code' );

		// Cart
		//$this->createIndex( 'idx_' . $this->prefix . 'cart_title', $this->prefix . 'cart', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_type', $this->prefix . 'cart', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_type_p', $this->prefix . 'cart', 'parentType' );

		// Cart Item
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_name', $this->prefix . 'cart_item', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_sku', $this->prefix . 'cart_item', 'sku' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_type', $this->prefix . 'cart_item', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_type_p', $this->prefix . 'cart_item', 'parentType' );

		// Order
		//$this->createIndex( 'idx_' . $this->prefix . 'order_title', $this->prefix . 'cart_order', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_type', $this->prefix . 'cart_order', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_type_p', $this->prefix . 'cart_order', 'parentType' );

		// Order Item
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_name', $this->prefix . 'cart_order_item', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_sku', $this->prefix . 'cart_order_item', 'sku' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_type', $this->prefix . 'cart_order_item', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_type_p', $this->prefix . 'cart_order_item', 'parentType' );

		// Voucher
		$this->createIndex( 'idx_' . $this->prefix . 'voucher_name', $this->prefix . 'cart_voucher', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'voucher_type', $this->prefix . 'cart_voucher', 'type' );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// UOM
		$this->dropIndex( 'idx_' . $this->prefix . 'uom_name', $this->prefix . 'cart_uom' );
		$this->dropIndex( 'idx_' . $this->prefix . 'uom_code', $this->prefix . 'cart_uom' );

		// Cart
		//$this->dropIndex( 'idx_' . $this->prefix . 'cart_title', $this->prefix . 'cart' );
		$this->dropIndex( 'idx_' . $this->prefix . 'cart_type', $this->prefix . 'cart' );
		$this->dropIndex( 'idx_' . $this->prefix . 'cart_type_p', $this->prefix . 'cart' );

		// Cart Item
		$this->dropIndex( 'idx_' . $this->prefix . 'cart_item_name', $this->prefix . 'cart_item' );
		$this->dropIndex( 'idx_' . $this->prefix . 'cart_item_sku', $this->prefix . 'cart_item' );
		$this->dropIndex( 'idx_' . $this->prefix . 'cart_item_type', $this->prefix . 'cart_item' );
		$this->dropIndex( 'idx_' . $this->prefix . 'cart_item_type_p', $this->prefix . 'cart_item' );

		// Order
		//$this->dropIndex( 'idx_' . $this->prefix . 'order_title', $this->prefix . 'cart_order' );
		$this->dropIndex( 'idx_' . $this->prefix . 'order_type', $this->prefix . 'cart_order' );
		$this->dropIndex( 'idx_' . $this->prefix . 'order_type_p', $this->prefix . 'cart_order' );

		// Order Item
		$this->dropIndex( 'idx_' . $this->prefix . 'order_item_name', $this->prefix . 'cart_order_item' );
		$this->dropIndex( 'idx_' . $this->prefix . 'order_item_sku', $this->prefix . 'cart_order_item' );
		$this->dropIndex( 'idx_' . $this->prefix . 'order_item_type', $this->prefix . 'cart_order_item' );
		$this->dropIndex( 'idx_' . $this->prefix . 'order_item_type_p', $this->prefix . 'cart_order_item' );

		// Voucher
		$this->dropIndex( 'idx_' . $this->prefix . 'voucher_name', $this->prefix . 'cart_voucher' );
		$this->dropIndex( 'idx_' . $this->prefix . 'voucher_type', $this->prefix . 'cart_voucher' );
	}

}

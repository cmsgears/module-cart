<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

/**
 * The cart migration inserts the database tables of cart module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m161005_034400_cart extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk		= Yii::$app->migration->isFk();
		$this->options	= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// UOM
		$this->upUom();
		$this->upUomConversion();

		// Cart
		$this->upCart();
		$this->upCartItem();

		// Order
		$this->upOrder();
		$this->upOrderItem();
		$this->upTransaction();

		// Voucher
		$this->upVoucher();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upUom() {

		$this->createTable( $this->prefix . 'cart_uom', [
			'id' => $this->bigPrimaryKey( 20 ),
			'code' => $this->string( Yii::$app->core->smallText )->notNull(),
			'name' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'group' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'base' => $this->boolean()->notNull()->defaultValue( false ),
			'active' => $this->boolean()->notNull()->defaultValue( false )
		], $this->options );
	}

	private function upUomConversion() {

		// 1 Uom Unit = Quantity * (1 Target Unit)
		$this->createTable( $this->prefix . 'cart_uom_conversion', [
			'id' => $this->bigPrimaryKey( 20 ),
			'uomId' => $this->bigInteger( 20 )->notNull(),
			'targetId' => $this->bigInteger( 20 )->notNull(),
			'quantity' => $this->float()->defaultValue( 0 )
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'uom_con_parent', $this->prefix . 'cart_uom_conversion', 'uomId' );
		$this->createIndex( 'idx_' . $this->prefix . 'uom_con_target', $this->prefix . 'cart_uom_conversion', 'targetId' );
	}

	private function upCart() {

		$this->createTable( $this->prefix . 'cart', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->defaultValue( null ),
			'modifiedBy' => $this->bigInteger( 20 )->defaultValue( null ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'token' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'guest' => $this->boolean()->notNull()->defaultValue( false ), // Guest cart for non-logged in users or guest checkout
			'name' => $this->string( Yii::$app->core->xxLargeText ), // Guest name
			'email' => $this->string( Yii::$app->core->xxLargeText ), // Guest email
			'mobile' => $this->string( Yii::$app->core->mediumText ), // Guest mobile
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'cart_creator', $this->prefix . 'cart', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_modifier', $this->prefix . 'cart', 'modifiedBy' );
	}

	private function upCartItem() {

		$this->createTable( $this->prefix . 'cart_item', [
			'id' => $this->bigPrimaryKey( 20 ),
			'cartId' => $this->bigInteger( 20 )->notNull(),
			'primaryUnitId' => $this->bigInteger( 20 ),
			'purchasingUnitId' => $this->bigInteger( 20 ),
			'quantityUnitId' => $this->bigInteger( 20 ),
			'weightUnitId' => $this->bigInteger( 20 ),
			'volumeUnitId' => $this->bigInteger( 20 ),
			'lengthUnitId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->defaultValue( null ),
			'modifiedBy' => $this->bigInteger( 20 )->defaultValue( null ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'name' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'sku' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'price' => $this->double()->notNull()->defaultValue( 0 ),
			'discount' => $this->double()->notNull()->defaultValue( 0 ),
			'total' => $this->double()->notNull()->defaultValue( 0 ),
			'primary' => $this->float()->notNull()->defaultValue( 0 ),
			'purchase' => $this->float()->notNull()->defaultValue( 0 ),
			'quantity' => $this->float()->notNull()->defaultValue( 0 ),
			'weight' => $this->float()->notNull()->defaultValue( 0 ),
			'volume' => $this->float()->notNull()->defaultValue( 0 ),
			'length' => $this->float()->notNull()->defaultValue( 0 ),
			'width' => $this->float()->notNull()->defaultValue( 0 ),
			'height' => $this->float()->notNull()->defaultValue( 0 ),
			'radius' => $this->float()->notNull()->defaultValue( 0 ),
			'keep' => $this->boolean()->notNull()->defaultValue( false ), // Keep the item for Order
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_parent', $this->prefix . 'cart_item', 'cartId' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_prim', $this->prefix . 'cart_item', 'primaryUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_pur', $this->prefix . 'cart_item', 'purchasingUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_qty', $this->prefix . 'cart_item', 'quantityUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_wt', $this->prefix . 'cart_item', 'weightUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_volume', $this->prefix . 'cart_item', 'volumeUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_length', $this->prefix . 'cart_item', 'lengthUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_creator', $this->prefix . 'cart_item', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'cart_item_modifier', $this->prefix . 'cart_item', 'modifiedBy' );
	}

	private function upOrder() {

		$this->createTable( $this->prefix . 'cart_order', [
			'id' => $this->bigPrimaryKey( 20 ),
			'baseId' => $this->bigInteger( 20 ),
			'cartId' => $this->bigInteger( 20 ),
			'userId' => $this->bigInteger( 20 ),
			'voucherId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'code' => $this->string( Yii::$app->core->xxLargeText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'subTotal' => $this->double()->notNull()->defaultValue( 0 ),
			'tax' => $this->float()->notNull()->defaultValue( 0 ),
			'shipping' => $this->float()->notNull()->defaultValue( 0 ),
			'total' => $this->double()->notNull()->defaultValue( 0 ),
			'discount' => $this->float()->notNull()->defaultValue( 0 ),
			'grandTotal' => $this->double()->notNull()->defaultValue( 0 ),
			'shipToBilling' => $this->boolean()->notNull()->defaultValue( false ),
			'token' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ), // Token for guest orders
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'eta' => $this->dateTime(),
			'deliveredAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'order_parent', $this->prefix . 'cart_order', 'baseId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_cart', $this->prefix . 'cart_order', 'cartId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_user', $this->prefix . 'cart_order', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_voucher', $this->prefix . 'cart_order', 'voucherId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_creator', $this->prefix . 'cart_order', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_modifier', $this->prefix . 'cart_order', 'modifiedBy' );
	}

	private function upOrderItem() {

		$this->createTable( $this->prefix . 'cart_order_item', [
			'id' => $this->bigPrimaryKey( 20 ),
			'orderId' => $this->bigInteger( 20 )->notNull(),
			'primaryUnitId' => $this->bigInteger( 20 ),
			'purchasingUnitId' => $this->bigInteger( 20 ),
			'quantityUnitId' => $this->bigInteger( 20 ),
			'weightUnitId' => $this->bigInteger( 20 ),
			'volumeUnitId' => $this->bigInteger( 20 ),
			'lengthUnitId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'name' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'sku' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'price' => $this->double()->notNull()->defaultValue( 0 ),
			'discount' => $this->float()->notNull()->defaultValue( 0 ),
			'total' => $this->float()->notNull()->defaultValue( 0 ),
			'primary' => $this->float()->notNull()->defaultValue( 0 ),
			'purchase' => $this->float()->notNull()->defaultValue( 0 ),
			'quantity' => $this->float()->notNull()->defaultValue( 0 ),
			'weight' => $this->float()->notNull()->defaultValue( 0 ),
			'volume' => $this->float()->notNull()->defaultValue( 0 ),
			'length' => $this->float()->notNull()->defaultValue( 0 ),
			'width' => $this->float()->notNull()->defaultValue( 0 ),
			'height' => $this->float()->notNull()->defaultValue( 0 ),
			'radius' => $this->float()->notNull()->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_parent', $this->prefix . 'cart_order_item', 'orderId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_prim', $this->prefix . 'cart_item', 'primaryUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_pur', $this->prefix . 'cart_order_item', 'purchasingUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_qty', $this->prefix . 'cart_order_item', 'quantityUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_wt', $this->prefix . 'cart_order_item', 'weightUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_volume', $this->prefix . 'cart_order_item', 'volumeUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_length', $this->prefix . 'cart_order_item', 'lengthUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_creator', $this->prefix . 'cart_order_item', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'order_item_modifier', $this->prefix . 'cart_order_item', 'modifiedBy' );
	}

	private function upTransaction() {

		$this->addColumn( $this->prefix . 'payment_transaction', 'orderId', $this->bigInteger( 20 )->after( 'userId' ) );

		// Index for order
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_order', $this->prefix . 'payment_transaction', 'orderId' );
	}

	private function upVoucher() {

		$this->createTable( $this->prefix . 'cart_voucher', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'code' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'amount' => $this->double()->notNull()->defaultValue( 0 ),
			'active' =>  $this->boolean()->notNull()->defaultValue( false ),
			'taxType' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'freeShipping' => $this->boolean()->notNull()->defaultValue( false ),
			'minPurchase' => $this->float()->notNull()->defaultValue( 0 ),
			'maxPurchase' => $this->float()->notNull()->defaultValue( 0 ),
			'maxDiscount' => $this->float()->notNull()->defaultValue( 0 ),
			'startTime' => $this->dateTime(),
			'endTime' => $this->dateTime(),
			'usageLimit' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'usageCount' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'voucher_creator', $this->prefix . 'cart_voucher', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'voucher_modifier', $this->prefix . 'cart_voucher', 'modifiedBy' );
	}

	private function generateForeignKeys() {

		// UOM
		$this->addForeignKey( 'fk_' . $this->prefix . 'uom_con_parent', $this->prefix . 'cart_uom_conversion', 'uomId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'uom_con_target', $this->prefix . 'cart_uom_conversion', 'targetId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );

		// Cart
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_creator', $this->prefix . 'cart', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_modifier', $this->prefix . 'cart', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Cart Item
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_parent', $this->prefix . 'cart_item', 'cartId', $this->prefix . 'cart', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_prim', $this->prefix . 'cart_item', 'primaryUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_pur', $this->prefix . 'cart_item', 'purchasingUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_qty', $this->prefix . 'cart_item', 'quantityUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_wt', $this->prefix . 'cart_item', 'weightUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_volume', $this->prefix . 'cart_item', 'volumeUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_length', $this->prefix . 'cart_item', 'lengthUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_creator', $this->prefix . 'cart_item', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'cart_item_modifier', $this->prefix . 'cart_item', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Order
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_parent', $this->prefix . 'cart_order', 'baseId', $this->prefix . 'cart_order', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_cart', $this->prefix . 'cart_order', 'cartId', $this->prefix . 'cart', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_user', $this->prefix . 'cart_order', 'userId', $this->prefix . 'core_user', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_voucher', $this->prefix . 'cart_order', 'voucherId', $this->prefix . 'cart_voucher', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_creator', $this->prefix . 'cart_order', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_modifier', $this->prefix . 'cart_order', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Order Item
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_parent', $this->prefix . 'cart_order_item', 'orderId', $this->prefix . 'cart_order', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_prim', $this->prefix . 'cart_order_item', 'primaryUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_pur', $this->prefix . 'cart_order_item', 'purchasingUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_qty', $this->prefix . 'cart_order_item', 'quantityUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_wt', $this->prefix . 'cart_order_item', 'weightUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_volume', $this->prefix . 'cart_order_item', 'volumeUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_length', $this->prefix . 'cart_order_item', 'lengthUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_creator', $this->prefix . 'cart_order_item', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'order_item_modifier', $this->prefix . 'cart_order_item', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Transaction
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_order', $this->prefix . 'payment_transaction', 'orderId', $this->prefix . 'cart_order', 'id', 'CASCADE' );

		// Voucher
		$this->addForeignKey( 'fk_' . $this->prefix . 'voucher_creator', $this->prefix . 'cart_voucher', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'voucher_modifier', $this->prefix . 'cart_voucher', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'cart_uom' );
		$this->dropTable( $this->prefix . 'cart_uom_conversion' );

		$this->dropTable( $this->prefix . 'cart' );
		$this->dropTable( $this->prefix . 'cart_item' );

		$this->dropTable( $this->prefix . 'cart_order' );
		$this->dropTable( $this->prefix . 'cart_order_item' );

		$this->dropTable( $this->prefix . 'cart_voucher' );
	}

	private function dropForeignKeys() {

		// UOM
		$this->dropForeignKey( 'fk_' . $this->prefix . 'uom_con_parent', $this->prefix . 'cart_uom_conversion' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'uom_con_target', $this->prefix . 'cart_uom_conversion' );

		// Cart
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_creator', $this->prefix . 'cart' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_modifier', $this->prefix . 'cart' );

		// Cart Item
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_parent', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_prim', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_pur', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_qty', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_wt', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_volume', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_length', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_creator', $this->prefix . 'cart_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'cart_item_modifier', $this->prefix . 'cart_item' );

		// Order
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_parent', $this->prefix . 'cart_order' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_cart', $this->prefix . 'cart_order' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_user', $this->prefix . 'cart_order' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_voucher', $this->prefix . 'cart_order' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_creator', $this->prefix . 'cart_order' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_modifier', $this->prefix . 'cart_order' );

		// Order Item
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_parent', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_prim', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_pur', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_qty', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_wt', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_volume', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_length', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_creator', $this->prefix . 'cart_order_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'order_item_modifier', $this->prefix . 'cart_order_item' );

		// Transaction
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_order', $this->prefix . 'payment_transaction' );

		// Voucher
		$this->dropForeignKey( 'fk_' . $this->prefix . 'voucher_creator', $this->prefix . 'cart_voucher' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'voucher_modifier', $this->prefix . 'cart_voucher' );
	}

}

<?php

class m160622_034400_cart extends \yii\db\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

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

		// Uom = Quantity * Target
		$this->createTable( $this->prefix . 'cart_uom_conversion', [
			'id' => $this->bigPrimaryKey( 20 ),
			'uomId' => $this->bigInteger( 20 )->notNull(),
			'targetId' => $this->bigInteger( 20 )->notNull(),
			'quantity' => $this->float( 2 )->defaultValue( 0 )
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'uom_con_parent', $this->prefix . 'cart_uom_conversion', 'uomId' );
		$this->createIndex( 'idx_' . $this->prefix . 'uom_con_target', $this->prefix . 'cart_uom_conversion', 'targetId' );
	}

	private function upCart() {

		$this->createTable( $this->prefix . 'cart', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'token' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
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
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'sku' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'price' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'discount' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'total' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'primary' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'purchase' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'quantity' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'weight' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'volume' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'length' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'width' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'height' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'radius' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'keep' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
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
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'subTotal' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'tax' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'shipping' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'total' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'discount' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'grandTotal' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'shipToBilling' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'eta' => $this->dateTime(),
			'deliveredAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'order_parent', $this->prefix . 'cart_order', 'baseId' );
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
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'sku' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'price' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'discount' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'primary' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'purchase' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'quantity' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'total' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'weight' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'volume' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'length' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'width' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'height' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'radius' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
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

		$this->addColumn( $this->prefix . 'payment_transaction', 'orderId', $this->bigInteger( 20 )->after( 'id' ) );

		// Index for order
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_order', $this->prefix . 'payment_transaction', 'orderId' );
	}

	private function upVoucher() {

		$this->createTable( $this->prefix . 'cart_voucher', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'amount' => $this->double( 2 )->notNull()->defaultValue( 0 ),
			'taxType' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'freeShipping' => $this->boolean()->notNull()->defaultValue( false ),
			'minPurchase' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'maxDiscount' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'startTime' => $this->dateTime(),
			'endTime' => $this->dateTime(),
			'usageLimit' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'usageCount' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
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

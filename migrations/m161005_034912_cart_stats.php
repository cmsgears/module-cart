<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelStats;
use cmsgears\cart\common\models\base\CartTables;

/**
 * The cart stats migration insert the default row count for all the tables available in
 * cart module. A scheduled console job can be executed to update these stats.
 *
 * @since 1.0.0
 */
class m161005_034912_cart_stats extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->options = Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Table Stats
		$this->insertTables();
	}

	private function insertTables() {

		$columns = [ 'parentId', 'parentType', 'name', 'type', 'count' ];

		$tableData = [
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_uom', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_uom_conversion', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_item', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_order', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_order_item', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_invoice', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_invoice_item', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'cart_voucher', 'rows', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_stats', $columns, $tableData );
	}

	public function down() {

		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_UOM ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_UOM_CONVERSION ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_CART ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_CART_ITEM ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_ORDER ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_ORDER_ITEM ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_INVOICE ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_INVOICE_ITEM ) );
		ModelStats::deleteByTable( CartTables::getTableName( CartTables::TABLE_VOUCHER ) );
	}

}

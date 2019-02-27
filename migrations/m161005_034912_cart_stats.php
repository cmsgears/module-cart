<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\models\resources\Stats;
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

		$columns = [ 'tableName', 'type', 'count' ];

		$tableData = [
			[ $this->prefix . 'cart_uom', 'rows', 0 ],
			[ $this->prefix . 'cart_uom_conversion', 'rows', 0 ],
			[ $this->prefix . 'cart', 'rows', 0 ],
			[ $this->prefix . 'cart_item', 'rows', 0 ],
			[ $this->prefix . 'cart_order', 'rows', 0 ],
			[ $this->prefix . 'cart_order_item', 'rows', 0 ],
			[ $this->prefix . 'cart_voucher', 'rows', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_stats', $columns, $tableData );
	}

	public function down() {

		Stats::deleteByTableName( CartTables::getTableName( CartTables::TABLE_UOM ) );
		Stats::deleteByTableName( CartTables::getTableName( CartTables::TABLE_UOM_CONVERSION ) );
		Stats::deleteByTableName( CartTables::getTableName( CartTables::TABLE_CART ) );
		Stats::deleteByTableName( CartTables::getTableName( CartTables::TABLE_CART_ITEM ) );
		Stats::deleteByTableName( CartTables::getTableName( CartTables::TABLE_ORDER ) );
		Stats::deleteByTableName( CartTables::getTableName( CartTables::TABLE_ORDER_ITEM ) );
		Stats::deleteByTableName( CartTables::getTableName( CartTables::TABLE_VOUCHER ) );
	}

}

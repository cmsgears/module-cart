<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\models\resources;

// CMG Imports
use cmsgears\cart\common\models\base\CartTables;

/**
 * UomConversion represent conversion factor from one unit to other. It must use the convertible
 * units as source and target.
 *
 * Conversion Formula:
 * target = quantity * source
 *
 * @property integer $id
 * @property integer $uomId
 * @property integer $targetId
 * @property float $quantity
 *
 * @since 1.0.0
 */
class UomConversion extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'uomId', 'targetId' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Other
			[ [ 'uomId', 'targetId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'quantity', 'number' ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'uomId' => 'Source UOM',
			'targetId' => 'Target UOM',
			'quantity' => 'Quantity'
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// UomConversion -------------------------

	/**
	 * Returns the source unit to be converted.
	 *
	 * @return Uom
	 */
	public function getSource() {

		$uomTable = CartTables::TABLE_UOM;

		return $this->hasOne( Uom::class, [ 'id' => 'uomId' ] )->from( "$uomTable as sourceUom" );
	}

	/**
	 * Returns the converted unit.
	 *
	 * @return Uom
	 */
	public function getTarget() {

		$uomTable = CartTables::TABLE_UOM;

		return $this->hasOne( Uom::class, [ 'id' => 'targetId' ] )->from( "$uomTable as targetUom" );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_UOM_CONVERSION );
	}

	// CMG parent classes --------------------

	// UomConversion -------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'source', 'target' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

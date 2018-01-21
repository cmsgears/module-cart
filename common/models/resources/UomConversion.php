<?php
namespace cmsgears\cart\common\models\resources;

// CMG Imports
use cmsgears\cart\common\models\base\CartTables;

/**
 * UomConversion Entity - The primary class.
 *
 * @property integer $id
 * @property integer $uomId
 * @property integer $targetId
 * @property float $quantity
 */
class UomConversion extends \cmsgears\core\common\models\base\Entity {

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

	public function rules() {

		return [
			// Required, Safe
			[ [ 'uomId', 'targetId' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Other
			[ [ 'uomId', 'targetId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'quantity', 'number' ]
		];
	}

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

	public function getSource() {

		$uomTable = CartTables::TABLE_UOM;

		return $this->hasOne( Uom::className(), [ 'id' => 'uomId' ] )->from( "$uomTable as sourceUom" );
	}

	public function getTarget() {

		$uomTable = CartTables::TABLE_UOM;

		return $this->hasOne( Uom::className(), [ 'id' => 'targetId' ] )->from( "$uomTable as targetUom" );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_UOM_CONVERSION;
	}

	// CMG parent classes --------------------

	// UomConversion -------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'source', 'target' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

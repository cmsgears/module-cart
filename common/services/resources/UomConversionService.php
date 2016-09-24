<?php
namespace cmsgears\cart\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\UomConversion;

use cmsgears\cart\common\services\interfaces\resources\IUomConversionService;

class UomConversionService extends \cmsgears\core\common\services\base\EntityService implements IUomConversionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\resources\UomConversion';

	public static $modelTable	= CartTables::TABLE_UOM_CONVERSION;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UomConversionService ------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
			'attributes' => [
				'source' => [
					'asc' => [ 'uomId' => SORT_ASC ],
					'desc' => ['uomId' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Source'
				],
				'target' => [
					'asc' => [ 'targetId' => SORT_ASC ],
					'desc' => [ 'targetId' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Target'
				]
			],
			'defaultOrder' => [ 'source' => SORT_ASC ]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'uomId', 'targetId', 'quantity' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// UomConversionService ------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}

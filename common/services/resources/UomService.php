<?php
namespace cmsgears\cart\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\Uom;

use cmsgears\cart\common\services\interfaces\resources\IUomService;

class UomService extends \cmsgears\core\common\services\base\EntityService implements IUomService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\resources\Uom';

	public static $modelTable	= CartTables::TABLE_UOM;

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

	// UomService ----------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'code' => [
					'asc' => [ 'code' => SORT_ASC ],
					'desc' => ['code' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Code'
				],
				'group' => [
					'asc' => [ 'group' => SORT_ASC ],
					'desc' => ['group' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Group'
				],
				'base' => [
					'asc' => [ 'base' => SORT_ASC ],
					'desc' => ['base' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Basic Unit'
				]
			],
			'defaultOrder' => [ 'group' => SORT_ASC ]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdNameMapByGroup( $group, $default = true ) {

		if( $default ) {

			return parent::getIdNameMap( [ 'conditions' => [ 'group' => $group ], 'prepend' => [ [ 'id' => 0, 'name' => 'Choose Unit' ] ] ] );
		}

		return parent::getIdNameMap( [ 'conditions' => [ 'group' => $group ] ] );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'code', 'group', 'base' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// UomService ----------------------------

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

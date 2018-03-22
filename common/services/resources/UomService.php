<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\resources;

// Yii Imports
use yii\data\Sort;

// CMG Imports
use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\Uom;

use cmsgears\cart\common\services\interfaces\resources\IUomService;

use cmsgears\core\common\services\base\ResourceService;

/**
 * UomService provide service methods of uom model.
 *
 * @since 1.0.0
 */
class UomService extends ResourceService implements IUomService {

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

	public function getIdNameMapByGroups( $groups, $default = true ) {

		if( $default ) {

			return parent::getIdNameMap( [ 'filters' => [ [ 'in', 'group', $groups ] ], 'prepend' => [ [ 'id' => 0, 'name' => 'Choose Unit' ] ] ] );
		}

		return parent::getIdNameMap( [ 'filters' => [ [ 'in', 'group', $groups ] ] ] );
	}

	public function getMapForConversion() {

		$objects		= parent::getObjectMap();
		$conversionMap	= [];

		foreach ( $objects as $key => $value ) {

			$group					= $value->getGroupStr();

			$conversionMap[ $key ]	= "$value->name, $group";
		}

		return $conversionMap;
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$this->updateBase( $model );

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'code', 'group', 'base' ];

		$this->updateBase( $model );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateBase( $model ) {

		if( $model->base ) {

			Uom::updateAll( [ 'base' => false ], "`base` = 1 AND `group` = $model->group" );
		}
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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

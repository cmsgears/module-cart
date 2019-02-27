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

	public static $modelClass = '\cmsgears\cart\common\models\resources\Uom';

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

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'code' => [
					'asc' => [ "$modelTable.code" => SORT_ASC ],
					'desc' => [ "$modelTable.code" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Code'
				],
				'group' => [
					'asc' => [ "$modelTable.group" => SORT_ASC ],
					'desc' => [ "$modelTable.group" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Group'
				],
				'base' => [
					'asc' => [ "$modelTable.base" => SORT_ASC ],
					'desc' => [ "$modelTable.base" => SORT_DESC ],
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

		$objects = parent::getObjectMap();

		$conversionMap = [];

		foreach( $objects as $key => $value ) {

			$group = $value->getGroupStr();

			$conversionMap[ $key ] = "$value->name, $group";
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

			$modelClass	= static::$modelClass;

			$modelClass::updateAll( [ 'base' => false ], "`base` = 1 AND `group` = $model->group" );
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

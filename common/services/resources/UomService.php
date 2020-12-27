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
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\cart\common\services\interfaces\resources\IUomService;

/**
 * UomService provide service methods of uom model.
 *
 * @since 1.0.0
 */
class UomService extends \cmsgears\core\common\services\base\ResourceService implements IUomService {

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

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

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
			'defaultOrder' => $defaultSort
		]);

		// Sort -------------

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		// Filters ----------

		// Params
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'base': {

					if( empty( $config[ 'conditions' ][ "$modelTable.base" ] ) ) {

						$config[ 'conditions' ][ "$modelTable.base" ] = true;
					}

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'code' => "$modelTable.code"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'code' => "$modelTable.code",
			'base' => "$modelTable.base"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdNameMapByGroup( $group, $config = [] ) {

		$config[ 'conditions' ][ 'group' ] = $group;

		return parent::getIdNameMap( $config );
	}

	public function getIdNameMapByGroups( $groups, $config = [] ) {

		$config[ 'filters' ][] = [ 'in', 'group', $groups ];

		return parent::getIdNameMap( $config );
	}

	public function getMapForConversion() {

		$objects = parent::getModelMap();

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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'code', 'group', 'base'
		];

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

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

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

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
use cmsgears\cart\common\services\interfaces\resources\IUomConversionService;

use cmsgears\core\common\services\base\ResourceService;

/**
 * UomConversionService provide service methods of uom conversion model.
 *
 * @since 1.0.0
 */
class UomConversionService extends ResourceService implements IUomConversionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\cart\common\models\resources\UomConversion';

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
				'source' => [
					'asc' => [ "$modelTable.uomId" => SORT_ASC ],
					'desc' => [ "$modelTable.uomId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Source'
				],
				'target' => [
					'asc' => [ "$modelTable.targetId" => SORT_ASC ],
					'desc' => [ "$modelTable.targetId" => SORT_DESC ],
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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

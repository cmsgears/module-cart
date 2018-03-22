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

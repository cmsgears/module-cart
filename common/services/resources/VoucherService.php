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
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\services\interfaces\resources\IVoucherService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * VoucherService provide service methods of voucher model.
 *
 * @since 1.0.0
 */
class VoucherService extends ModelResourceService implements IVoucherService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\resources\Voucher';

	public static $parentType	= CartGlobal::TYPE_VOUCHER;

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

	// VoucherService ------------------------

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
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'amount' => [
					'asc' => [ "$modelTable.amount" => SORT_ASC ],
					'desc' => [ "$modelTable.amount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Amount'
				]
			]
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

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// VoucherService ------------------------

	// Data Provider ------

	// Read ---------------

	public function getByCode( $code ) {
		
		$modelClass = self::$modelClass;
		
		return $modelClass::getByCode( $code );
	}
	
	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'code', 'amount', 'startTime', 'endTime', 'type', 'active' ];

		$user = Yii::$app->core->getAppUser();

		$model->modifiedBy = $user->id;

		// Update Cart
		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}
	
	// Delete -------------
}

<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use yii\data\Sort;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;

use cmsgears\cart\common\services\interfaces\entities\IVoucherService;

class VoucherService extends \cmsgears\core\common\services\base\EntityService implements IVoucherService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\entities\Voucher';

	public static $modelTable	= CartTables::TABLE_VOUCHER;

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

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => [ 'name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'type' => [
					'asc' => [ 'type' => SORT_ASC ],
					'desc' => [ 'type' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'amount' => [
					'asc' => [ 'amount' => SORT_ASC ],
					'desc' => [ 'amount' => SORT_DESC ],
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

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// VoucherService ------------------------

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

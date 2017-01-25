<?php
namespace cmsgears\cart\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\resources\OrderHistory;

use cmsgears\cart\common\services\interfaces\resources\IOrderHistoryService;

class OrderHistoryService extends \cmsgears\core\common\services\base\EntityService implements IOrderHistoryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\resources\OrderHistory';

	public static $modelTable	= CartTables::TABLE_ORDER_HISTORY;

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

	// OrderHistoryService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
			'attributes' => [
				'order' => [
					'asc' => [ 'orderId' => SORT_ASC ],
					'desc' => ['orderId' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'type' => [
					'asc' => [ 'type' => SORT_ASC ],
					'desc' => [ 'type' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'creator' => [
					'asc' => [ 'createdBy' => SORT_ASC ],
					'desc' => [ 'createdBy' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Creator'
				]
			],
			//'defaultOrder' => [ 'source' => SORT_ASC ]
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

	// OrderHistoryService -------------------

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

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
use cmsgears\cart\common\services\interfaces\resources\ITransactionService;

/**
 * TransactionService provide service methods of transaction model.
 *
 * @since 1.0.0
 */
class TransactionService extends \cmsgears\payment\common\services\resources\TransactionService implements ITransactionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\cart\common\models\resources\Transaction';

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

	// TransactionService --------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'order' => [
					'asc' => [ "$modelTable.orderId" => SORT_ASC ],
					'desc' => [ "$modelTable.orderId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'mode' => [
					'asc' => [ "$modelTable.mode" => SORT_ASC ],
					'desc' => [ "$modelTable.mode" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Mode'
				],
				'service' => [
					'asc' => [ "$modelTable.service" => SORT_ASC ],
					'desc' => [ "$modelTable.service" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Service'
				],
				'amount' => [
					'asc' => [ "$modelTable.amount" => SORT_ASC ],
					'desc' => [ "$modelTable.amount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Amount'
				],
				'currency' => [
					'asc' => [ "$modelTable.currency" => SORT_ASC ],
					'desc' => [ "$modelTable.currency" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Currency'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				],
				'pdate' => [
					'asc' => [ "$modelTable.processedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.processedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Processed At'
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

		// Searching --------

		// Reporting --------

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByOrderId( $orderId ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.orderId" => $orderId ] ] );
	}

	// Read ---------------

	// Read - Models ---

	public function getByOrderId( $orderId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByOrderId( $orderId );
	}

	public function getFirstByOrderId( $orderId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstByOrderId( $orderId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createByParams( $params = [], $config = [] ) {

		$model = $this->getModelObject();

		$model->orderId = isset( $params[ 'orderId' ] ) ? $params[ 'orderId' ] : null;

		// Config
		$config[ 'model' ] = $model;

		// Return Transaction
		return parent::createByParams( $params, $config );
	}

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// TransactionService --------------------

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

<?php
namespace cmsgears\cart\common\services\entities;

// CMG Imports
use cmsgears\payment\common\models\entities\Transaction;

use cmsgears\cart\common\services\interfaces\entities\ITransactionService;

class TransactionService extends \cmsgears\payment\common\services\entities\TransactionService implements ITransactionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\entities\Transaction';

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

		$modelClass	= self::$modelClass;
		$modelTable	= self::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
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
			'defaultOrder' => [ 'cdate' => SORT_DESC ]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'title' => "$modelTable.title", 'desc' => "$modelTable.description" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title", 'desc' => "$modelTable.description"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByOrderId( $orderId ) {

		$modelTable = self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.orderId" => $orderId ] ] );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createByParams( $params = [], $config = [] ) {

		$transaction				= new Transaction();

		// Mandatory
		$transaction->orderId		= $params[ 'orderId' ];

		$config[ 'transaction' ]	= $transaction;

		// Return Transaction
		return parent::createByParams( $params, $config );
	}

	// Update -------------

	// Delete -------------

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

<?php
namespace cmsgears\cart\common\services\system;

// Yii Imports
use \Yii;
use yii\data\Sort;
use yii\db\Query;

// CMG Imports
use cmsgears\payment\common\models\base\PaymentTables;
use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\entities\Order;

use cmsgears\cart\common\services\interfaces\system\ISalesService;

use cmsgears\core\common\utilities\DateUtil;

class SalesService extends \yii\base\Component implements ISalesService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

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

	// SalesService --------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getSalesData( $duration, $config = [] ) {

		$dates			= [];
		$transactions	= [];
		$transactionskv = [];
		$amount			= [];
		$base			= isset( $config[ 'base' ] ) ? $config[ 'base' ] : false;
		$children		= isset( $config[ 'children' ] ) ? $config[ 'children' ] : false;

		$statusComplete	= Order::STATUS_COMPLETED;

		$txnTable		= PaymentTables::TABLE_TRANSACTION;
		$orderTable		= CartTables::TABLE_ORDER;

		$query			= new Query();

		$query->select( [ "date(`$txnTable`.`createdAt`) as date", 'sum( amount ) as amount' ] );
		$query->from( $txnTable );

		$query->join( 'LEFT JOIN', $orderTable, "orderId = `$orderTable`.`id`" );
		$query->where( " $orderTable.status=$statusComplete" );

		if( $base ) {

			$query->andWhere( " $orderTable.baseId IS NULL" );
		}
		else if( $children ) {

			$query->andWhere( " $orderTable.baseId IS NOT NULL" );
		}

		switch( $duration ) {

			case 0:
			{
				// Current Week - Starting with Sun
				$dates			= DateUtil::getCurrentWeekDates();
				$transactions	= $query->andWhere( "YEARWEEK( `$txnTable`.`createdAt` ) = YEARWEEK( CURRENT_DATE ) " )
										->groupBy( [ 'date' ] )
										->all();
				break;
			}
			case 1:
			{

				// Last Week - Starting with Sun
				$dates			= DateUtil::getLastWeekDates();
				$transactions	= $query->andWhere( "YEARWEEK( `$txnTable`.`createdAt` ) = YEARWEEK( CURRENT_DATE - INTERVAL 7 DAY ) " )
										->groupBy( [ 'date' ] )
										->all();

				break;
			}
			case 2:
			{
				// This Month
				$dates			= DateUtil::getCurrentMonthDates();
				$transactions	= $query->andWhere( "MONTH( `$txnTable`.`createdAt` ) = ( MONTH( NOW() ) ) AND YEAR( `$txnTable`.`createdAt` ) = YEAR( NOW() ) " )
										->groupBy( [ 'date' ] )
										->all();

				break;
			}
			case 3:
			{
				// Last Month
				$dates			= DateUtil::getLastMonthDates();
				$transactions	= $query->andWhere( "MONTH( `$txnTable`.`createdAt` ) = ( MONTH( NOW() ) - 1 ) AND YEAR( `$txnTable`.`createdAt` ) = YEAR( NOW() ) " )
										->groupBy( [ 'date' ] )
										->all();

				break;
			}
		}

		foreach ( $transactions as $transaction ) {

			$transactionskv[ $transaction[ 'date' ] ] = $transaction[ 'amount' ];
		}

		foreach ( $dates as $date ) {

			if( isset( $transactionskv[ $date ] ) ) {

				$amount[]	= $transactionskv[ $date ];
			}
			else {

				$amount[]	= 0;
			}
		}

		return $amount;
	}

	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SalesService --------------------------

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

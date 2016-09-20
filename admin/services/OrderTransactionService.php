<?php
namespace cmsgears\cart\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;
use yii\db\Query;

// CMG Imports
use cmsgears\cart\common\models\entities\CartTables;
use cmsgears\cart\common\models\entities\Order;
use cmsgears\cart\common\models\entities\OrderTransaction;

use cmsgears\core\common\utilities\DateUtil;

class OrderTransactionService extends \cmsgears\cart\common\services\OrderTransactionService {

	// Static Methods ----------------------------------------------

	// Read -------------

	public static function getSalesData( $duration ) {

		$dates			= [];
		$transactions	= [];
		$transactionskv = [];
		$amount			= [];

		$statusPaid		= [ Order::STATUS_PAID, Order::STATUS_DELIVERED ];
		$statusPaid		= join( ",", $statusPaid );

		$orderTable		= CartTables::TABLE_ORDER;
		$txnTable		= CartTables::TABLE_ORDER_TRANSACTION;
		$query			= new Query();

		$query->select( [ "date(`$txnTable`.`createdAt`) as date", 'sum( amount ) as amount' ] );
		$query->from( $txnTable );

		$query->join( 'LEFT JOIN', $orderTable, "orderId = `$orderTable`.`id`" );
		$query->where( " $orderTable.status in ( $statusPaid )" );

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
}

?>
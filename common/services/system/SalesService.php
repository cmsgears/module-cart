<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\system;

// Yii Imports
use yii\db\Query;

// CMG Imports
use cmsgears\cart\common\models\entities\Order;

use cmsgears\cart\common\services\interfaces\system\ISalesService;

use cmsgears\core\common\utilities\DateUtil;

/**
 * SalesService provide methods specific to sales data and graphs.
 *
 * @since 1.0.0
 */
class SalesService extends \cmsgears\core\common\services\base\SystemService implements ISalesService {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SalesService --------------------------

	public function getSalesData( $duration, $config = [] ) {

		$ignoreSite = isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

		$transactions	= [];
		$transactionskv = [];

		$dates	= [];
		$amount	= [];

		$base		= isset( $config[ 'base' ] ) ? $config[ 'base' ] : false;
		$children	= isset( $config[ 'children' ] ) ? $config[ 'children' ] : false;

		$statusComplete	= Order::STATUS_COMPLETED;

		$txnTable	= Yii::$app->get( 'transactionService' )->getModelTable();
		$orderTable	= Yii::$app->get( 'orderService' )->getModelTable();

		$query = new Query();

		$query->select( [ "date(`$txnTable`.`createdAt`) as date", 'sum( amount ) as amount' ] );
		$query->from( $txnTable );

		$query->join( 'LEFT JOIN', $orderTable, "orderId = `$orderTable`.`id`" );

		if( $ignoreSite ) {

			$query->where( "$orderTable.status=$statusComplete" );
		}
		else {

			$query->where( "$txnTable.siteId=$siteId AND $orderTable.status=$statusComplete" );
		}

		if( $base ) {

			$query->andWhere( " $orderTable.baseId IS NULL" );
		}
		else if( $children ) {

			$query->andWhere( " $orderTable.baseId IS NOT NULL" );
		}

		switch( $duration ) {

			case 0: {

				// Current Week - Starting with Sun
				$dates = DateUtil::getCurrentWeekDates();

				$transactions = $query->andWhere( "YEARWEEK( `$txnTable`.`createdAt` ) = YEARWEEK( CURRENT_DATE ) " )
								->groupBy( [ 'date' ] )
								->all();

				break;
			}
			case 1: {

				// Last Week - Starting with Sun
				$dates = DateUtil::getLastWeekDates();

				$transactions = $query->andWhere( "YEARWEEK( `$txnTable`.`createdAt` ) = YEARWEEK( CURRENT_DATE - INTERVAL 7 DAY ) " )
								->groupBy( [ 'date' ] )
								->all();

				break;
			}
			case 2: {

				// This Month
				$dates = DateUtil::getCurrentMonthDates();

				$transactions = $query->andWhere( "MONTH( `$txnTable`.`createdAt` ) = ( MONTH( NOW() ) ) AND YEAR( `$txnTable`.`createdAt` ) = YEAR( NOW() ) " )
								->groupBy( [ 'date' ] )
								->all();

				break;
			}
			case 3: {

				// Last Month
				$dates = DateUtil::getLastMonthDates();

				$transactions = $query->andWhere( "MONTH( `$txnTable`.`createdAt` ) = ( MONTH( NOW() ) - 1 ) AND YEAR( `$txnTable`.`createdAt` ) = YEAR( NOW() ) " )
								->groupBy( [ 'date' ] )
								->all();

				break;
			}
		}

		foreach( $transactions as $transaction ) {

			$transactionskv[ $transaction[ 'date' ] ] = $transaction[ 'amount' ];
		}

		foreach( $dates as $date ) {

			if( isset( $transactionskv[ $date ] ) ) {

				$amount[] = $transactionskv[ $date ];
			}
			else {

				$amount[] = 0;
			}
		}

		return $amount;
	}

}

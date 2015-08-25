<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\entities\CartTables; 
use cmsgears\cart\common\models\entities\OrderHistory;

class OrderHistoryService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------
	 
	// Read ---------------- 

	public static function findById( $id ) {
		
		return OrderHistory::findById( $id );
	}

	public static function findByOrderId( $oderId ) {
		
		return OrderHistory::findByOrderId( $oderId );
	}

	// Data Provider ------

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new OrderHistory(), $config );
	}

	// Create -----------

	public static function create( $order ) {

		// Set Attributes
		$user					= Yii::$app->user->getIdentity();

		$orderHistory 			= new OrderHistory();

		$orderHistory->createdBy	= $user->id;
		$orderHistory->orderId		= $order->id;
		$orderHistory->status		= $order->status;

		$orderHistory->save();

		// Return OrderHistory
		return $orderHistory;
	}

	// Update ----------- 

	// Delete -----------

}

?>
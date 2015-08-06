<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\entities\CartTables; 
use cmsgears\cart\common\models\entities\OrderTransaction;

use cmsgears\core\common\utilities\DateUtil;

class OrderTransactionService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------
	 
	// Read ---------------- 

	public static function findById( $id ) {

		return OrderTransaction::findById( $id );
	}

	public static function findByOrderId( $orderId ) {

		return OrderTransaction::findByOrderId( $orderId );
	}

	// Data Provider ------

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new OrderTransaction(), $config );
	}

	// Create -----------

	public static function create( $orderId, $code, $type, $mode, $amount, $message ) {

		// Set Attributes
		$user						= Yii::$app->user->getIdentity();

		$transaction				= new OrderTransaction();
		$transaction->createdBy		= $user->id;
		$transaction->orderId		= $orderId;
		$transaction->code			= $code;
		$transaction->type			= $type;
		$transaction->mode			= $mode;
		$transaction->amount		= $amount;
		$transaction->description	= $message;
		$transaction->createdAt		= DateUtil::getDateTime();

		$transaction->save();

		// Return OrderTransaction
		return $transaction;
	}

	// Update ----------- 

}

?>
<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ITransactionService extends \cmsgears\payment\common\services\interfaces\entities\ITransactionService {

	// Data Provider ------

	public function getPageByOrderId( $orderId );

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

	// Items --------------

}

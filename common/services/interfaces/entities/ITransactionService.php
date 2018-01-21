<?php
namespace cmsgears\cart\common\services\interfaces\entities;

interface ITransactionService extends \cmsgears\payment\common\services\interfaces\entities\ITransactionService {

	// Data Provider ------

	public function getPageByOrderId( $orderId );

	// Read ---------------

	// Read - Models ---

	public function getByOrderId( $orderId );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}

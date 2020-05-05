<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\interfaces\resources;

// CMG Imports
use cmsgears\payment\common\services\interfaces\resources\ITransactionService as IBaseTransactionService;

/**
 * ITransactionService declares methods specific to order transactions.
 *
 * @since 1.0.0
 */
interface ITransactionService extends IBaseTransactionService {

	// Data Provider ------

	public function getPageByOrderId( $orderId );

	// Read ---------------

	// Read - Models ---

	public function getByOrderId( $orderId );

	public function getFirstByOrderId( $orderId );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

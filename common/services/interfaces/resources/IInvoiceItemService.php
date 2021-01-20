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
use cmsgears\core\common\services\interfaces\base\IModelResourceService;

/**
 * IInvoiceItemService declares methods specific to invoice items.
 *
 * @since 1.0.0
 */
interface IInvoiceItemService extends IModelResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByInvoiceId( $invoiceId );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromOrderItem( $invoice, $orderItem, $config = [] );

	// Update -------------

	public function updateStatus( $model, $status );

	public function paid( $model, $config = [] );

	public function cancel( $model, $config = [] );

	public function deliver( $model, $config = [] );

	public function back( $model, $config = [] );

	public function receive( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

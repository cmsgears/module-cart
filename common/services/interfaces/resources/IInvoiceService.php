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
use cmsgears\core\common\services\interfaces\base\IMultiSite;

/**
 * IInvoiceService declares methods specific to invoices.
 *
 * @since 1.0.0
 */
interface IInvoiceService extends IModelResourceService, IMultiSite {

	// Data Provider ------

	public function getPageByUserId( $userId, $config = [] );

	public function getPageByUserIdType( $userId, $type, $config = [] );

	public function getPageByUserIdParentType( $userId, $parentType, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByTitle( $title );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getCountByParent( $parentId, $parentType );

	public function getCountByUserId( $userId );

	// Create -------------

	public function createFromOrder( $invoice, $order, $config = [] );

	// Update -------------

	public function updateCode( $model, $code );

	public function updateStatus( $model, $status );

	public function approve( $model, $config = [] );

	public function hold( $model, $config = [] );

	public function cancel( $model, $config = [] );

	public function paid( $model, $config = [] );

	public function confirm( $model, $config = [] );

	public function refund( $model, $config = [] );

	public function complete( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

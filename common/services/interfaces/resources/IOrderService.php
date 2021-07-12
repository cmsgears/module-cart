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
use cmsgears\core\common\services\interfaces\base\IStatus;

/**
 * IOrderService declares methods specific to orders.
 *
 * @since 1.0.0
 */
interface IOrderService extends IModelResourceService, IMultiSite, IStatus {

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

	public function getStatusCountByUserIdParentType( $userId, $parentType );

	// Create -------------

	public function createFromCart( $order, $cart, $config = [] );

	// Update -------------

	public function updateCode( $model, $code );

	public function updateStatus( $model, $status );

	public function processCancel( $model, $checkChildren = true, $checkBase = true );

	public function approve( $model, $config = [] );

	public function place( $model, $config = [] );

	public function hold( $model, $config = [] );

	public function reject( $model, $config = [] );

	public function cancel( $model, $config = [] );

	public function fail( $model, $config = [] );

	public function paid( $model, $config = [] );

	public function refund( $model, $config = [] );

	public function confirm( $model, $config = [] );

	public function process( $model, $config = [] );

	public function ship( $model, $config = [] );

	public function deliver( $model, $config = [] );

	public function back( $model, $config = [] );

	public function dispute( $model, $config = [] );

	public function complete( $model, $config = [] );

	public function updateBaseStatus( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

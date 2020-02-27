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
 * IOrderService declares methods specific to orders.
 *
 * @since 1.0.0
 */
interface IOrderService extends IModelResourceService {

	// Data Provider ------

	public function getPageByUserId( $userId, $config = [] );

	public function getPageByUserIdType( $userId, $type, $config = [] );

	public function getPageByUserIdParentType( $userId, $parentType, $config = [] );

	// Read ---------------

	public function getCountByParent( $parentId, $parentType );

	public function getCountByUserId( $userId );

	// Read - Models ---

	public function getByTitle( $title );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromCart( $order, $cart, $config = [] );

	// Update -------------

	public function updateStatus( $model, $status );

	public function processCancel( $order, $checkChildren = true, $checkBase = true );

	public function hold( $order );

	public function cancel( $order );

	public function approve( $order );

	public function reject( $order );

	public function place( $order );

	public function paid( $order );

	public function confirm( $order );

	public function process( $order );

	public function ship( $order );

	public function deliver( $order );

	public function back( $order );

	public function dispute( $order );

	public function complete( $order );

	public function updateBaseStatus( $order );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

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

	// Read - Models ---

	public function getByTitle( $title );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getCountByParent( $parentId, $parentType );

	public function getCountByUserId( $userId );

	// Create -------------

	public function createFromCart( $order, $cart, $config = [] );

	// Update -------------

	public function updateStatus( $model, $status );

	public function processCancel( $order, $checkChildren = true, $checkBase = true );

	public function approve( $order, $config = [] );

	public function place( $order, $config = [] );

	public function hold( $order, $config = [] );

	public function reject( $order, $config = [] );

	public function cancel( $order, $config = [] );

	public function paid( $order, $config = [] );

	public function confirm( $order, $config = [] );

	public function process( $order, $config = [] );

	public function ship( $order, $config = [] );

	public function deliver( $order, $config = [] );

	public function back( $order, $config = [] );

	public function dispute( $order, $config = [] );

	public function complete( $order, $config = [] );

	public function updateBaseStatus( $order, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

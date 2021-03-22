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
 * IVoucherService declares methods specific to discount vouchers.
 *
 * @since 1.0.0
 */
interface IVoucherService extends IModelResourceService, IMultiSite {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCode( $code );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function activate( $model );

	public function block( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

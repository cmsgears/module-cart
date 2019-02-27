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
use cmsgears\core\common\services\interfaces\base\IResourceService;

/**
 * IUomService declares methods specific to UOM.
 *
 * @since 1.0.0
 */
interface IUomService extends IResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdNameMapByGroup( $group, $default = true );

	public function getIdNameMapByGroups( $groups, $default = true );

	public function getMapForConversion();

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateBase( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}

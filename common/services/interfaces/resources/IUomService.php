<?php
namespace cmsgears\cart\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IUomService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdNameMapByGroup( $group, $default = true );

	public function getIdNameMapByGroups( $groups, $default = true );

	public function getMapForConversion();

	// Create -------------

	// Update -------------

	public function updateBase( $model );

	// Delete -------------

}

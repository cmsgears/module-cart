<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ICartService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	public function setCartItemService( ICartItemService $cartItemService );

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByToken( $token );

	public function getByUserId( $userId );

	public function getByParent( $parentId, $parentType, $first = true );

	public function getActiveByParent( $parentId, $parentType );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createByUserId( $userId );

	// Update -------------

	public function updateStatus( $model, $status, $config = [] );

	public function setAbandoned( $model, $config = [] );

	// Delete -------------

	// Items --------------

	public function addItem( $model, $item, $config = [] );
}

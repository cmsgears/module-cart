<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ICartItemService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCartId( $id );

	public function getByUserId( $userId );

	public function getByParentCartId( $parentId, $parentType, $cartId );

	public function getParentIdObjectMap( $cart );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByCartId( $cartId );
}

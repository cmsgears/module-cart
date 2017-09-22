<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IEntityService;

interface ICartItemService extends IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCartId( $cartId );

	public function getByParentCartId( $parentId, $parentType, $cartId );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByCartId( $cartId );
}

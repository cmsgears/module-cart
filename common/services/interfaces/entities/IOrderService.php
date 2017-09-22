<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IEntityService;

interface IOrderService extends IEntityService {

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType );

	public function getPageByUserId( $userId );

	public function getPageByUserIdParentType( $userId, $parentType );

	// Read ---------------

	public function getCountByParent( $parentId, $parentType );

	public function getCountByUserId( $userId );

	// Read - Models ---

	public function getByTitle( $title );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createFromCart( $order, $cart, $config = [] );

	// Update -------------

	public function updateStatus( $model, $status );

	public function cancel( $order, $checkChildren = true, $checkBase = true );

	public function approve( $order );

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

}

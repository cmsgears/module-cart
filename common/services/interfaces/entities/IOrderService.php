<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IOrderService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	public function setOrderItemService( IOrderItemService $orderItemService );

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType );

	// Read ---------------

	public function getCountByParent( $parentId, $parentType );

	public function getCountByUserId( $userId );

	// Read - Models ---

	public function getByTitle( $title );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createFromCart( $order, $message, $cart, $config = [] );

	// Update -------------

	public function updateStatus( $model, $status );

	public function approve( $order );

	public function place( $order );

	public function paid( $order );

	public function confirm( $order );

	public function process( $order );

	public function deliver( $order );

	public function complete( $order );

	// Delete -------------

}

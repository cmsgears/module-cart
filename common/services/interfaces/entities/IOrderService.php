<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IOrderService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

    // Data Provider ------

    public function getPageByParent( $parentId, $parentType );

    // Read ---------------

    public function getCountByParent( $parentId, $parentType );

    public function getCountByUserId( $userId );

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Create -------------

    // Update -------------

    public function updateStatus( $model, $status );

    public function confirmOrder( $order );

    public function placeOrder( $order );

    public function updateStatusToPaid( $order );

    // Delete -------------

}

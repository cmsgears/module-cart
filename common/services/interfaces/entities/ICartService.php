<?php
namespace cmsgears\cart\common\services\interfaces\entities;

// Yii Imports
use \Yii;

interface ICartService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    public function getByParentIdParentType( $parentId, $parentType );

    // Read - Lists ----

    // Read - Maps -----

    // Create -------------

    // Update -------------

    // Delete -------------

}

<?php
namespace cmsgears\cart\frontend\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\entities\Order;

class OrderService extends \cmsgears\cart\common\services\OrderService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Order(), $config );
	}

	public static function getPaginationByParentIdParentType( $parentId, $parentType ) {

		return self::getPagination( [ 'conditions' => [ 'parentId' => $parentId, 'parentType' => $parentType ] ] );
	}
}

?>
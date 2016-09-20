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
				'status' => [
					'asc' => [ 'status' => SORT_ASC ],
					'desc' => ['status' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'status',
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